<?php
class Pagination
{
    public $totalCount;
    public $pageSize;
    public $page;
    public $offset;
    public $limit;
    public $url = [];

    public function __construct($paginationConfig = [])
    {
        if (array_key_exists("totalCount", $paginationConfig)) {
            $this->totalCount = $paginationConfig['totalCount'];
        }

        if (array_key_exists("pageSize", $paginationConfig)) {
            $this->pageSize = $paginationConfig['pageSize'];
        }
        if (array_key_exists("page", $paginationConfig)) {
            $this->page = $paginationConfig['page'];
        }
    }
    function setTotalCount($totalCount = 1)
    {
        $this->totalCount = $totalCount;
    }
    function setPagesize($pageSize = 25)
    {
        $this->pageSize = $pageSize;
    }
    function setPage($page = 1)
    {
        $this->page = $page;
    }
    function getOffset()
    {
        $offset = ($this->page - 1) * $this->pageSize;
        return $offset;
    }
    function getLimit()
    {
        return $this->pageSize;
    }
    function render()
    {
        $styleNormal = 'min-width:20px; border:1px solid #9fcff8;color:#2170b4;
        padding: 1px 3px; margin-left:5px; background-color:#c0e2ff';

        $styleActive = 'min-width:20px; border:1px solid #1265ad;color:#fff;
        padding: 1px 3px; margin-left:5px; background-color:#2170b4';

        $str = "";
        $pageCount = ceil($this->totalCount / $this->pageSize);
        if ($pageCount > 1) {
            for ($i = 1; $i <= $pageCount; $i++) {
                $url = $this->url;
                $url["page"] = $i;
                // array_push($url, ["page"=>$i]);
                $style = ($i == $this->page) ? $styleActive : $styleNormal;
                $str .= Html::a(
                    Html::tag("span", $i, ['style' => $style]),
                    $url,
                );
            }
        }

        return $str;
    }
}
