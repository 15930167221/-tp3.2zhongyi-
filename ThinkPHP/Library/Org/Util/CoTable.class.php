<?php
/**
 * Created by PhpStorm.
 * User: dark
 * Date: 2017-03-22
 * Time: 14:53
 */
class CoTable
{
    private $HORIZONTAL_TOTAL_FIELD = '合计';
    private $VERTICAL_TOTAL_FIELD = '合计';
    private $data;
    private $topPivot;
    private $leftPivot;
    private $measure;
    private $horizontalColumn = array ();
    private $verticalColumn = array ();
    private $pivotValue = array ();
    private $isHorizontalTotal = true;
    private $isVerticalTotal = true;
    private $horizontalTotal = null;
    private $verticalTotal = null;
    private $title = '日期';
    /**
     * 初始化交叉表
     */
    private function InitPivot()
    {
        $this->topPivot;

        foreach ( $this->data as $d )
        {
            $this->horizontalColumn [] = $d [$this->leftPivot];
            $this->verticalColumn [] = $d [$this->topPivot];
        }
        $this->horizontalColumn = array_unique ( $this->horizontalColumn );
        $this->verticalColumn = array_unique ( $this->verticalColumn );
        $reasult = array ();
        foreach ( $this->horizontalColumn as $h )
        {
            foreach ( $this->verticalColumn as $v )
            {
                $this->pivotValue [$h] [$v] = 0;
            }
            //var_dump($this->pivotValue);
        }
    }
    /**
     * 填充数据
     */
    private function fillData()
    {
        foreach ( $this->data as $row )
        {
            $this->pivotValue [$row [$this->leftPivot]] [$row [$this->topPivot]] += $row [$this->measure];
        }
        if ($this->isHorizontalTotal)
        {
            $this->setHorizontalTotal ();
        }
        if ($this->isVerticalTotal)
        {
            $this->setVerticalTotal ();
        }
    }
    /**
     * 设置纵向合计
     */
    private function setVerticalTotal()
    {
        $this->verticalColumn [] = $this->VERTICAL_TOTAL_FIELD;
        foreach ( $this->horizontalColumn as $i )
        {
            $rowsum = 0;
            foreach ( $this->verticalColumn as $j )
            {
                $rowsum += $this->pivotValue [$i] [$j];
            }
            $this->pivotValue [$i] [$this->TOTAL_FIELD] = $rowsum;
        }
    }
    /**
     * 设置横向合计
     */
    private function setHorizontalTotal()
    {
        $this->horizontalColumn [] = $this->HORIZONTAL_TOTAL_FIELD;
        foreach ( $this->verticalColumn as $i )
        {
            $rowsum = 0;
            foreach ( $this->horizontalColumn as $j )
            {
                $rowsum += $this->pivotValue [$j] [$i];
            }
            $this->pivotValue [$this->HORIZONTAL_TOTAL_FIELD] [$i] = $rowsum;
        }
    }
    /**
     * 渲染
     */
    function Render()
    {
        echo '<pre>';
        print_r ( $this->pivotValue );
    }
    /**
     * 渲染为table
     */
    function RenderToTable()
    {
        $resault = "<!--startprint1--><table border='0' width='250'>\n";
//        $resault .= "<caption>费用汇总统计</caption>\n";
        $resault .= "<thead><tr><th>$this->title</th>\n";
        foreach ( $this->verticalColumn as $value )
        {
            $resault .= "<th>$value</th>\n";
        }
        $resault .= "</tr></thead>\n";
        foreach ( $this->horizontalColumn as $i )
        {
            $resault .= "<tr><td>$i</td>\n";
            foreach ( $this->pivotValue [$i] as $value )
            {
                if($value==0){
                    $value="";
                }
                $value=number_format($value, 2, '.', ''); //保留两位小数
                $resault .= "<td>$value</td>\n";
            }
            $resault .= "</tr>\n";
        }
        $resault .= "</table><!--endprint1-->";
        return $resault;
    }
    /**
     * 构造交叉表
     * @param $data 数据源
     * @param $topPivot 头栏目字段
     * @param $leftPivot 左栏目字段
     * @param $measure 计算量
     */
    function __construct(array $data, $topPivot, $leftPivot, $measure)
    {
        $this->data = $data;
        $this->leftPivot = $leftPivot;
        $this->topPivot = $topPivot;
        $this->measure = $measure;
        $this->horizontalColumn = array ();
        $this->verticalColumn = array ();
        $this->InitPivot ();
        $this->fillData ();
    }
}