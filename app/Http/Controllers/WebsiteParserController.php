<?php

namespace App\Http\Controllers;

use App\CompaniesList;
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;

class WebsiteParserController extends Controller
{

    public function process($from=0,$to=1)
    {
        for ($from; $from <= $to; $from++) {
            if($from==0 || $from==1){
                $url="http://www.mycorporateinfo.com/industry/section/F";
            }
            else{
                $url="http://www.mycorporateinfo.com/industry/section/F/page/".$from;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            $dom = HtmlDomParser::str_get_html($response);
            //table(1)->tbody(16)->tr(4)
            //$dom->find('table')[0]->children()[0]->children()[0]->children()[0]->innertext;
            $tablerecords=$dom->find('table')[0]->children()[0]->children();
            if($tablerecords){
                foreach($tablerecords as $tablerecord){
                    if($tablerecord->children()[0]->innertext!='CIN'){
                        $cin=$tablerecord->children()[0]->innertext;
                        $cname=$tablerecord->children()[1]->children()[0]->innertext;
                        $clink=$tablerecord->children()[1]->children()[0]->attr['href'];
                        $cclass=$tablerecord->children()[2]->innertext;
                        $cstatus=$tablerecord->children()[3]->innertext;
                        CompaniesList::updateOrCreate(['cin' => $cin],['company_name' => $cname, 'company_link'=>$clink,'status' => $cstatus,'company_class'=>$cclass]);
                    }
                }
                echo 'records inserted successfully for page no:-'.$from.'<br>';
            }
            else{
                echo 'error while inserting data';
            }
        }
    }

}
