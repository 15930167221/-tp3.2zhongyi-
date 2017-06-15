<?php
namespace Home\Model;

use Think\Model;

class XydrugcfDetialModel extends Model
{
    protected $tableName = 'xydrugcf_detial';

    /**根据cf_id分组，拿到健康档案中西药处置信息
     * @param array $condition 查询条件
     * @return mixed 返回病历信息的二维数组
     */
    public function getXyChuZhiInf(array $condition){

        //return $this->query("SELECT cf_id,MIN(fs) as fs FROM xydrugcf_detial WHERE BR_ID='".session(id)."' AND XH='".session(xh)."' AND cf_flag='1' GROUP BY cf_id;");
        return $this->field('cf_id,MIN(fs) as fs')->where($condition)->group('cf_id')->select();
//        SELECT cf_id,MIN(fs) as fs FROM xydrugcf_detial WHERE BR_ID='2017030200006' AND XH='3' AND cf_flag='1' GROUP BY cf_id;
    }

    /**根据西药名称分组，查询病人所患的疾病名称
     * @param array $condition 查询条件
     * @return mixed
     */
    public function getXyName(array $condition){
        return $this->where($condition)->field('xy_name,cf_id')->order('cf_id asc')->select();
    }
}