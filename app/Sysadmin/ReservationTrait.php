<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/6
 * Time: 14:44
 */

namespace App\Sysadmin;

use App\Models\SchoolApply;
use App\Models\InsuranceApply;
//use Zilf\Support\Request;
use Zilf\Facades\Request;
trait ReservationTrait
{
    /**
     * 报名
     * @return mixed
     */
    public function school_apply()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $urlPattern = toRoute('order/school_apply/(:num)?'.$_SERVER['QUERY_STRING']);

        $vars = Request::query()->all();
        $model = SchoolApply::find()->joinWith('driverSchool',false);
        if(isset($vars['name']) && !empty($vars['name'])){
            $model->andWhere(['like','school_apply.name',trim($vars['name'])]);
        }

        if(isset($vars['mobile']) && !empty($vars['mobile'])){
            $model->andWhere(['like','school_apply.mobile',trim($vars['mobile'])]);
        }

        if(isset($vars['school_name']) && !empty($vars['school_name'])){
            $model->andWhere(['like','driver_school.name',trim($vars['school_name'])]);
        }

        if(isset($vars['driver_type']) && !empty($vars['driver_type'])){
            $model->andWhere(['like','school_apply.driver_type',trim($vars['driver_type'])]);
        }

        if(isset($vars['pay_status']) && !empty($vars['pay_status'])){
            $model->andWhere(['like','school_apply.pay_status',trim($vars['pay_status'])]);
        }

        $result = SchoolApply::getModelPageList($model,'school_apply.*,driver_school.name as driver_name,driver_school.price','school_apply.id desc',null,null, $urlPattern, $currentPage);
        return $this->render('order/school_apply',['list'=>$result['list'],'page'=>$result['page'],'vars'=>$vars]);
    }

    /**
     * 保险
     * @return mixed
     */
    public function insure_apply()
    {

        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $urlPattern = toRoute('order/insure_apply/(:num)?'.$_SERVER['QUERY_STRING']);

        $vars = Request::query()->all();
        $model = InsuranceApply::find()->joinWith('insuranceCompany',false);

        if(isset($vars['user']) && !empty($vars['user'])){
            $model->andWhere(['like','insure_apply.user',trim($vars['user'])]);
        }

        if(isset($vars['mobile']) && !empty($vars['mobile'])){
            $model->andWhere(['like','insure_apply.mobile',trim($vars['mobile'])]);
        }

        if(isset($vars['company_name']) && !empty($vars['company_name'])){
            $model->andWhere(['like','insurance_company.company_name',trim($vars['company_name'])]);
        }
        if(isset($vars['pay_status']) && !empty($vars['pay_status'])){
            $model->andWhere(['like','insure_apply.pay_status',trim($vars['pay_status'])]);
        }
        $result = InsuranceApply::getModelPageList($model,'insure_apply.*,insurance_company.company_name,insurance_company.price','insure_apply.id desc',null,null, $urlPattern, $currentPage);
        return $this->render('order/insure_apply',['list'=>$result['list'],'page'=>$result['page'],'vars'=>$vars]);
    }
}