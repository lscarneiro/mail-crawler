<?php

namespace App\Console\Commands;

use App\Email;
use App\Url;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;

class Crawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audima Crawler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function linkExists($url)
    {
        return Url::where('url', $url)->first() != null;
    }

    private function saveLink($url)
    {
        if (!$this->linkExists($url)) {
            Url::create([
                'url' => $url
            ]);
        }
    }

    private function emailExists($email)
    {
        $email = strtolower($email);
        if (ends_with($email, ['png', 'jpg', 'gif', 'jpeg'])) {
            return true;
        }
        return Email::where('email', $email)->first() != null;
    }

    private function saveEmail($email)
    {
        if (!$this->emailExists($email)) {
            Email::create([
                'email' => $email
            ]);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $urlObject = Url::where('visited', 'no')->first();
            if ($urlObject == null) {
                $this->info('No more url to visit');
                return;
            }
            do {
                $this->info($urlObject->url);
                $url = $urlObject->url;
                $client = new \GuzzleHttp\Client();
                try {
                    $response = $client->get($url);
                } catch (ClientException $cliEx) {
                    if (in_array($cliEx->getCode(), [404, 403, 401, 500])) {
                        $urlObject->visited = 'yes';
                        $urlObject->save();
                        $urlObject = Url::where('visited', 'no')->first();
                    } else {
                        throw $cliEx;
                    }
                    continue;
                } catch (ConnectException $cliEx) {
                    $urlObject->visited = 'yes';
                    $urlObject->save();
                    $urlObject = Url::where('visited', 'no')->first();
                    continue;
                }
                if ($response->getStatusCode() != 200) {
                    if ($cliEx->getCode() == 404) {
                        $urlObject->visited = 'yes';
                        $urlObject->save();
                        $urlObject = Url::where('visited', 'no')->first();
                    }
                    continue;
                }

                $urlParts = parse_url($url);
                $domain = "{$urlParts['scheme']}://{$urlParts['host']}";
                $body = $response->getBody()->__toString();
                $emailCount = preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $body, $emails);
                if ($emailCount > 0) {
                    foreach ($emails[0] as $email) {
                        $email = html_entity_decode($email);
                        $this->saveEmail($email);
                    }
                }
                $linkCount = preg_match_all('/<a href=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?>/i', $body, $links);
                if ($linkCount > 0) {
                    foreach ($links[1] as $link) {
                        if (starts_with($link, 'http')) {
                            $this->saveLink($link);
                        } else if (in_array($link, ['"/', '/', 'javascript:void'])) {
                            continue;
                        } else if (starts_with($link, ['javascript:void', '#', 'tel:','mailto:'])) {
                            continue;
                        } else if (str_contains($link, ['http', 'https'])) {
                            $query = parse_url($link, PHP_URL_QUERY);
                            parse_str(html_entity_decode($query), $parsedQuery);
                            foreach ($parsedQuery as $key => $value) {
                                if (!str_contains($value, ['http', 'https'])) {
                                    continue;
                                }
                                if (starts_with($value, 'http')) {
                                    $this->saveLink($value);
                                    break;
                                }
                                $pos = strpos($value, 'http');
                                $this->saveLink(substr($value, $pos));
                                break;
                            }
                        } else {
                            $urlToSave = $domain . $link;
                            $this->saveLink($urlToSave);
                        }
                    }
                }

                $urlObject->visited = 'yes';
                $urlObject->save();
                $urlObject = Url::where('visited', 'no')->first();
            } while ($urlObject != null);

        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }


    }
}
