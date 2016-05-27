<?php
    class RU
    {
        private $menuArray, $tags;

        public function __construct()
        {
            $ruHtml = $this->getUrl('http://ru.ufsc.br/ru/');

            $ruDom = new DOMDocument();
        	@$ruDom->loadHTML($ruHtml);

            $menuTable = $ruDom->getElementsByTagName('table')[0];
            $tableLines = $menuTable->getElementsByTagName('tr');
            $this->tags = $this->buildTagArray($tableLines[0]);
            $this->menuArray = $this->buildMenuArray($tableLines, $this->tags);
            $this->tags = array_unique($this->tags);
            unset($this->tags[0]);
        }

        public function getMenu($day)
        {
            return $this->menuArray[$day];
        }

        public function getTags()
        {
            return $this->tags;
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

        private function buildTagArray($tableHeader)
        {
            $tagsDom = $tableHeader->getElementsByTagName('td');
            foreach ($tagsDom as $tag) {
                $value = trimAll($tag->textContent);
                $width = (int)$tag->getAttribute('colspan');
                if ($width < 1)
                {
                    $width = 1;
                }
                for ($i = 0; $i < $width; $i++)
                {
                    $tags[] = $value;
                }
            }
            return $tags;
        }

        private function buildMenuArray($tableLines, $tags)
        {
            $display = $this->getDisplayOptions();
            foreach ($tableLines as $lineKey => $line)
            {
                if ($lineKey !== 0)
                {
                    $day = array();
                    $cells = $line->getElementsByTagName('td');
                    foreach ($cells as $cellKey => $cell)
                    {
                        if ($cellKey == 0 || $display[$tags[$cellKey]])
                        {
                            $dishes = explode('/', $cell->textContent);
                            foreach ($dishes as $dish)
                            {
                                $day[$tags[$cellKey]][] = trimAll($dish);
                            }
                        }
                    }
                    $menuArray[] = $day;
                }
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
                date_default_timezone_set("America/Sao_Paulo");
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

    if(!defined('VIEW')){
        header('Location:.');
    }
