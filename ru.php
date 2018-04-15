<?php
    class RU
    {
        private $menuArray, $tags;

        public function __construct()
        {
            date_default_timezone_set("America/Sao_Paulo");

            $ruHtml = $this->getHtml();

            $ruDom = new DOMDocument();
        	@$ruDom->loadHTML($ruHtml);

            $menuTable = $ruDom->getElementsByTagName('table')[0];
            $tableLines = $menuTable->getElementsByTagName('tr');
            $this->tags = $this->getTagArray();
            $this->menuArray = $this->buildMenuArray($tableLines, $this->tags);
            $this->tags = array_unique($this->tags);
            unset($this->tags[0]);
        }

        public function getMenu($day)
        {
            if (isset($_GET["json"]))
            {
                header('Content-Type: application/json; charset=utf-8');
                $array = $this->menuArray[$day];
                $array = fixArrayKey($array);
                die(json_encode($array,  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
            return $this->menuArray[$day];
        }

        public function getTags()
        {
            return $this->tags;
        }

        private function getHtml()
        {
            $cache = $_SERVER['DOCUMENT_ROOT'] . '/cache/ru';
            $url = 'http://ru.ufsc.br/ru/';
            $expires = @filemtime($cache) + 60 * 5;
            $now = time();
            if(file_exists($cache) && $now < $expires)
            {
                return file_get_contents($cache);
            }
            else
            {
                $content = $this->getUrl($url);
                file_put_contents($cache, $content);
                return $content;
            }
        }

        private function getUrl($url)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_URL => $url
            ));
            $content = curl_exec($curl);
            curl_close($curl);
            $content = str_replace('<br />', ' <br />', $content);
            $content = str_replace(')', ')/', $content);
            $content = preg_replace("/\n|\r/", '', $content);
            return $content;
        }

        private function getTagArray()
        {
            return ["Dia", "Complemento", "Complemento", "Prato Principal", "Acompanhamento", "Salada", "Sobremesa"];
        }

        private function buildMenuArray($tableLines, $tags)
        {
            $display = $this->getDisplayOptions();
            foreach ($tableLines as $lineKey => $line)
            {
                $day = array();
                $cells = $line->getElementsByTagName('td');
                foreach ($cells as $cellKey => $cell)
                {
                    if ($cellKey == 0 || $display[$tags[$cellKey]])
                    {
                        if ($cellKey == 0) {
                            $day[$tags[$cellKey]][] = $cell->firstChild->textContent;
                            $day[$tags[$cellKey]][] = $cell->lastChild->textContent;
                        } else {
                            $dishes = explode('/', $cell->textContent);
                            foreach ($dishes as $dish)
                            {
                                $day[$tags[$cellKey]][] = trimAll($dish);
                            }
                            $day[$tags[$cellKey]] = array_filter($day[$tags[$cellKey]]);
                        }
                    }
                }
                $menuArray[] = $day;
            }
            return $menuArray;
        }

        public function getDay()
        {
            if (isset($_GET["day"]))
            {
                $day = (int) $_GET["day"];
            }
            if (!isset($day) || $day >= 7 || $day < 0)
            {
                $day = date("N", time()) - 1;
            }
            return $day;
        }

        public function getDisplayOptions()
        {
            if (isset($_COOKIE["display"]))
            {
                $display = (array) json_decode($_COOKIE["display"]);
            }
            else
            {
                foreach ($this->tags as $tag) {
                    $display[$tag] = true;
                }
            }
            return $display;
        }
    }

    function trimAll($string)
    {
        return trim($string, " \t\n\r\0\x0B\xC2\xA0");
    }

    function fixArrayKey($elem)
    {
        if (is_array($elem)) {
            foreach ($elem as $key=>$value)
                $newElem[preg_replace('/[^A-Za-z0-9\-]/', '', $key)]=$value;
        }
        return $newElem;
    }

    if(!defined('VIEW')){
        header('Location:/');
    }
