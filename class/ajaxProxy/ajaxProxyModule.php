<?php

namespace rpf\ajaxProxyModule;
use rpf\system\module;

/**
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class ajaxProxyModule extends module
{
    /**
     * @var string
     */
    protected $requestUrl;

    /**
     * @var array
     */
    protected $requestPost;

    /**
     * @var array
     */
    protected $rp2Response;

    /**
     * @var array
     */
    protected $response;

    public function __construct()
    {
        parent::_construct();

    }

    protected function proxy($url = NULL, $post = NULL)
    {
        $url =  $url != NULL ?: $_SERVER['SCRIPT_URL'];
        $post = $post != NULL ?: $_POST;


    }

    /**
     * @param $url
     * @param array $data
     * @param array|null $headers
     * @return bool|string
     *
     * @author Eduardo Cuomo
     * @link http://stackoverflow.com/users/717267/eduardo-cuomo
     * @see http://stackoverflow.com/a/15161640
     */
    private function returnRequest($url, array $data, array $headers = null) {
        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        if (!is_null($headers)) {
            $params['http']['header'] = '';
            foreach ($headers as $k => $v) {
                $params['http']['header'] .= "$k: $v\n";
            }
        }
        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);
        if ($fp) {
            return stream_get_contents($fp);
        } else {
            return false;
        }
    }
}

