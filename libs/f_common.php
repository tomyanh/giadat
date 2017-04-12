<?php
/**
 * Created by TuanAnh
 * Date: 2016-04-30
 * Time: 9:43 AM
 */


    header("Content-type: application/json");
    include_once ('dal.php');



    switch ($_REQUEST['act']){
        case 'cbo_tenduong':
            if (!empty($_POST["id"]))
                act_chon_tenduong($_POST["id"]);
            break;

        case 'cbo_chonhuyen':
            if (!empty($_POST["huyen"]))
                act_chon_huyen($_POST["huyen"]);
            break;

        case 'cbo_chonxa':
            if (!empty($_POST["xa"]))
            act_chon_xa($_POST["xa"],$_POST["duong"]);
            break;

        case 'xem_chitiet':
            if (!empty($_POST["id"]))
                act_td_xem_chitiet($_POST["id"],$_POST["vitri"],$_POST["sonam"]);  //
            break;

        case 'tinh_bang5':
            if (!empty($_POST["id"]))
                act_tinh_bang5($_POST["id"],$_POST["vitri"],$_POST["sonam"]);  //
            break;

    }


    function act_chon_tenduong($duong){
        //lay khu vuc
        $tt = db_get_thongtin_tenduong($duong);

        $output=null;
        $output =  array('loaikhuvuc'=>$tt["loai_khu_vuc"],
                         'heso_d'=>$tt["he_so_d"],
                         'heso_kn'=>$tt["he_so_kn"],
                         'heso_kp'=>$tt["he_so_kp"]);

        echo json_encode($output,JSON_FORCE_OBJECT);

    }

    function act_chon_huyen($huyen){
        $results = db_get_danhsachPhuongXa($huyen);

        $lst="<option value=''>Chọn Xã, phường, thị trấn...</option>";
        foreach($results as $px){

           $lst = $lst . "<option value='". $px["id"]."'>" . $px["name"] ."</option>";

        }

        //$output="ok";
        $output =  array('phuongxa'=>$lst);

        echo json_encode($output,JSON_FORCE_OBJECT);

    }

function act_chon_xa($xa,$duong){
    $lst = db_get_ds_tenduong_theo_khuvuc_all($xa,$duong);

    $str = "<table class='table table-bordered'> "
            . "    <tr> "
            . "        <th>TÊN ĐƯỜNG</th><th>TỪ</th><th>ÐẾN</th><th>GIÁ</th> "
            . "    </tr> " ;

    $str_row_temp = "    <tr> "
                . "        <td>{ten_duong}</td><td>{tu}</td><td>{den}</td>"
                //. "        <td><button type='submit' data-target='#divKetqua'  onclick='act_td_xem({id});'>Xem</button></td> " //data-toggle='modal'
                .  "        <td><a href='#' data-target='#divKetqua' title='Xem giá đất' onclick='act_td_xem({id});'>Xem</a> "
                . "    </tr> ";


        foreach($lst as $row){

            $str_row = $str_row_temp;

            $str_row = str_replace("{ten_duong}",$row["ten_duong"],$str_row);
            $str_row = str_replace("{tu}",$row["tu"],$str_row);
            $str_row = str_replace("{den}",$row["den"],$str_row);
            $str_row = str_replace("{id}",$row["id"],$str_row);

            $str = $str . $str_row;
        }

    $str = $str . "</table> ";


    //$output="ok";
    $output =  array('ds_duong'=>$str);

    echo json_encode($output,JSON_FORCE_OBJECT);

}

function act_td_xem_chitiet($duong, $vitri,$sonam){


    if ($vitri == "MT")
        $ttd = db_get_thongtin_tenduong($duong);
    else
        $ttd = db_get_thongtin_tenduong_hem($duong, $vitri);

    //error_log("act_td_xem_chitiet:1");
    $banggia = get_bang_gia_html($duong, $vitri);

    //error_log("act_td_xem_chitiet:2");
    $banggia2 = get_bang_gia2_html($duong, $vitri);  //gia theo qd 67

    //error_log("act_td_xem_chitiet:3");
    $data_cmd = get_bang_gia_chuyendoi($duong, $vitri);

    //error_log("act_td_xem_chitiet:3-1");
    $banggia3 = get_html_bang3($data_cmd);
    //error_log("act_td_xem_chitiet:3-2");
    $banggia4 = get_html_bang4($data_cmd);

    //error_log("act_td_xem_chitiet:4");
    $data_cmd_b3 = get_bang_gia_chuyendoi_b3($duong, $vitri, $sonam);
    $banggia5 = get_html_bang5($data_cmd_b3);

    //error_log("act_td_xem_chitiet:2");
    $output = array(
        'ten_duong' => $ttd["ten_duong"],
        'tu' => $ttd["tu"],
        'den' => $ttd["den"],
        'vung' => $ttd["loai_vung"],
        'loai' => $ttd["loai_khu_vuc"],// .'-' . $ttd["id"].'-' . $ttd["loai_vung"],
        'loai_kv2' => $ttd["loai_khuvuc_2"],
        'duongchinh' => $ttd["ten_duongchinh"],
        'hs_d' => $ttd["he_so_d"],
        'hs_kn' => $ttd["he_so_kn"],
        'hs_kp' => $ttd["he_so_kp"],
        'bang_gia' => $banggia,
        'bang_gia2' => $banggia2,
        'bang_gia3' => $banggia3,
        'bang_gia4' => $banggia4,
        'bang_gia5' => $banggia5
    );


    echo json_encode($output, JSON_FORCE_OBJECT);

    //error_log("act_td_xem_chitiet:end");




}

function act_tinh_bang5($duong, $vitri,$sonam){

    $data_cmd_b3 = get_bang_gia_chuyendoi_b3($duong,$vitri,$sonam);
    $banggia5= get_html_bang5($data_cmd_b3);


    $output =  array(
        'bang_gia5' => $banggia5
    );

    echo json_encode($output,JSON_FORCE_OBJECT);

}


function get_bang_gia_html($duong,$hem){
    //error_log("get_bang_gia_html:start");
    $html = file_get_contents('../htmls/table_template.php', true);


    if ($hem =="MT")
        $gia = db_get_bang_gia_td($duong);
    else
        $gia = db_get_bang_gia_hem_td($duong,$hem);

    //error_log("get_bang_gia_html:in");

    //thiet lap tieu de cho tooltip
    $td0 = file_get_contents('../htmls/tieude_tooltip/bang1_td.php', true);
    $html = str_replace("{TITLE_BANG_1_TD}", $td0 ,$html);

    $td1 = file_get_contents('../htmls/tieude_tooltip/TM_DV.php', true);
    $html = str_replace("{TITLE_TM_DV}", $td1 ,$html);

    $td2 = file_get_contents('../htmls/tieude_tooltip/CLN_NKH.php', true);
    $html = str_replace("{TITLE_CLN_NKH}", $td2 ,$html);

    $td3 = file_get_contents('../htmls/tieude_tooltip/SXKD.php', true);
    $html = str_replace("{TITLE_SXKD}", $td3 ,$html);


    $html = str_replace("{d1_lua}", format_num($gia[0]["LUA"]) ,$html);
    $html = str_replace("{d1_cln}", format_num($gia[0]["CLN"]),$html);
    $html = str_replace("{d1_rsx}", format_num($gia[0]["RSX"]),$html);
    $html = str_replace("{d1_nts}", format_num($gia[0]["NTS"]),$html);
    $html = str_replace("{d1_odt}", format_num($gia[0]["ODT"]),$html);
    $html = str_replace("{d1_tdv}", format_num($gia[0]["TMDV"]),$html);
    $html = str_replace("{d1_skc}", format_num($gia[0]["SKC"]),$html);

    $html = str_replace("{d2_lua}", format_num($gia[1]["LUA"]),$html);
    $html = str_replace("{d2_cln}", format_num($gia[1]["CLN"]),$html);
    $html = str_replace("{d2_odt}", format_num($gia[1]["ODT"]),$html);
    $html = str_replace("{d2_tdv}", format_num($gia[1]["TMDV"]),$html);
    $html = str_replace("{d2_skc}", format_num($gia[1]["SKC"]),$html);

    $html = str_replace("{d3_lua}", format_num($gia[2]["LUA"]),$html);
    $html = str_replace("{d3_cln}", format_num($gia[2]["CLN"]),$html);
    $html = str_replace("{d3_odt}", format_num($gia[2]["ODT"]),$html);
    $html = str_replace("{d3_tdv}", format_num($gia[2]["TMDV"]),$html);
    $html = str_replace("{d3_skc}", format_num($gia[2]["SKC"]),$html);

    $html = str_replace("{d4_lua}", format_num($gia[3]["LUA"]),$html);
    $html = str_replace("{d4_cln}", format_num($gia[3]["CLN"]),$html);
    $html = str_replace("{d4_odt}", format_num($gia[3]["ODT"]),$html);
    $html = str_replace("{d4_tdv}", format_num($gia[3]["TMDV"]),$html);
    $html = str_replace("{d4_skc}", format_num($gia[3]["SKC"]),$html);
    //error_log("get_bang_gia_html:out");

    return $html;
    //error_log("get_bang_gia_html:end");
}


function get_bang_gia2_html($duong,$hem){
    //error_log("get_bang_gia2_html:start");

    $html2 = file_get_contents('../htmls/table_template_b2.php', true);

    if ($hem =="MT")
        $gia = db_get_bang_gia_td($duong);
    else
        $gia = db_get_bang_gia_hem_td($duong,$hem);

    //error_log("get_bang_gia2_html:in");


    $td2_0 = file_get_contents('../htmls/tieude_tooltip/bang2_td.php', true);
    $html2 = str_replace("{TITLE_BANG_2_TD}", $td2_0 ,$html2);

    $td1 = file_get_contents('../htmls/tieude_tooltip/TM_DV.php', true);
    $html2 = str_replace("{TITLE_TM_DV}", $td1 ,$html2);

    $td2 = file_get_contents('../htmls/tieude_tooltip/CLN_NKH.php', true);
    $html2 = str_replace("{TITLE_CLN_NKH}", $td2 ,$html2);

    $td3 = file_get_contents('../htmls/tieude_tooltip/SXKD.php', true);
    $html2 = str_replace("{TITLE_SXKD}", $td3 ,$html2);

    $html2 = str_replace("{d1_lua}", format_num($gia[0]["LUA_2"]),$html2);
    $html2 = str_replace("{d1_cln}", format_num($gia[0]["CLN_2"]),$html2);
    $html2 = str_replace("{d1_rsx}", format_num($gia[0]["RSX_2"]),$html2);
    $html2 = str_replace("{d1_nts}", format_num($gia[0]["NTS_2"]),$html2);
    $html2 = str_replace("{d1_odt}", format_num($gia[0]["ODT_2"]),$html2);
    $html2 = str_replace("{d1_tdv}", format_num($gia[0]["TMDV_2"]),$html2);
    $html2 = str_replace("{d1_skc}", format_num($gia[0]["SKC_2"]),$html2);

    $html2 = str_replace("{d2_lua}", format_num($gia[1]["LUA_2"]),$html2);
    $html2 = str_replace("{d2_cln}", format_num($gia[1]["CLN_2"]),$html2);
    $html2 = str_replace("{d2_odt}", format_num($gia[1]["ODT_2"]),$html2);
    $html2 = str_replace("{d2_tdv}", format_num($gia[1]["TMDV_2"]),$html2);
    $html2 = str_replace("{d2_skc}", format_num($gia[1]["SKC_2"]),$html2);

    $html2 = str_replace("{d3_lua}", format_num($gia[2]["LUA_2"]),$html2);
    $html2 = str_replace("{d3_cln}", format_num($gia[2]["CLN_2"]),$html2);
    $html2 = str_replace("{d3_odt}", format_num($gia[2]["ODT_2"]),$html2);
    $html2 = str_replace("{d3_tdv}", format_num($gia[2]["TMDV_2"]),$html2);
    $html2 = str_replace("{d3_skc}", format_num($gia[2]["SKC_2"]),$html2);

    $html2 = str_replace("{d4_lua}", format_num($gia[3]["LUA_2"]),$html2);
    $html2 = str_replace("{d4_cln}", format_num($gia[3]["CLN_2"]),$html2);
    $html2 = str_replace("{d4_odt}", format_num($gia[3]["ODT_2"]),$html2);
    $html2 = str_replace("{d4_tdv}", format_num($gia[3]["TMDV_2"]),$html2);
    $html2 = str_replace("{d4_skc}", format_num($gia[3]["SKC_2"]),$html2);


    return $html2;
    //error_log("get_bang_gia2_html:end");
}

function get_html_bang3($data){

    $html = file_get_contents('../htmls/table_template_bang3.php', true);
    $html_sub="";

    $dong=0;
    foreach($data as $row){
        $dong += 1;
        $sTencot="div_b3_d".$dong;

        $html_sub.="<tr>
                    <th>" . $row["PHAM_VI"] ."</th>
                    <td><div id='".$sTencot."c1'>". format_num($row["LUA_DAT_O_THM"]) ."</div></td>
                    <td><div id='".$sTencot."c2'>". format_num($row["LUA_DAT_O_VHM"]) ."</div></td>
                    <td><div id='".$sTencot."c3'>". format_num($row["LUA_TMDV70"]) ."</div></td>
                    <td><div id='".$sTencot."c4'>". format_num($row["LUA_SXKD70"]) ."</div></td>
                </tr>";
    }

    $html= str_replace("<!--dulieu_bang3-->",$html_sub,$html);

    return $html;
    //error_log("get_bang_gia_html:end");
}

function get_html_bang4($data){

    $html = file_get_contents('../htmls/table_template_bang4.php', true);
    $html_sub="";

    $dong=0;
    foreach($data as $row){
        $dong += 1;
        $sTencot="div_b4_d".$dong;

        $html_sub.="<tr>
                    <th>" . $row["PHAM_VI"] ."</th>
                    <td><div id='".$sTencot."c1'>". format_num($row["CLN_DAT_O_THM"]) ."</div></td>
                    <td><div id='".$sTencot."c2'>". format_num($row["CLN_DAT_O_VHM"]) ."</div></td>
                    <td><div id='".$sTencot."c3'>". format_num($row["CLN_TMDV70"]) ."</div></td>
                    <td><div id='".$sTencot."c4'>". format_num($row["CLN_SXKD70"]) ."</div></td>
                </tr>";
    }

    $html= str_replace("<!--dulieu_bang4-->",$html_sub,$html);

    return $html;
    //error_log("get_bang_gia_html:end");
}


function get_html_bang5($data){

    $html = file_get_contents('../htmls/table_template_bang5.php', true);
    $html_sub="";

    $dong=0;
    foreach($data as $row){
        $dong += 1;
        $sTencot="div_b5_d".$dong;

        $html_sub.="<tr>
                    <th>" . $row["PHAM_VI"] ."</th>
                    <td><div id='".$sTencot."c1'>". format_num($row["B3_CLN_TMDV"]) ."</div></td>
                    <td><div id='".$sTencot."c2'>". format_num($row["B3_CLN_SXKD"]) ."</div></td>
                    <td><div id='".$sTencot."c3'>". format_num($row["B3_SXKD_DAT_O_THM"]) ."</div></td>
                    <td><div id='".$sTencot."c4'>". format_num($row["B3_SXKD_DAT_O_VHM"]) ."</div></td>
                    <td><div id='".$sTencot."c5'>". format_num($row["B3_TMDV_DAT_O_THM"]) ."</div></td>
                    <td><div id='".$sTencot."c6'>". format_num($row["B3_TMDV_DAT_O_VHM"]) ."</div></td>
                    <td><div id='".$sTencot."c7'>". format_num($row["B3_SXKD_TMDV"]) ."</div></td>
                </tr>";
    }

    $html= str_replace("<!--dulieu_bang5-->",$html_sub,$html);

    return $html;
    //error_log("get_bang_gia_html:end");
}





////////////////////////////////////////////////////////////////////

function format_num1($num){
    $x = number_format($num, 1,',', '');

    if (fmod($x,1)==0)
        $x = number_format($num, 0, ',', '.');
    else
        $x = number_format($num, 1, ',', '.');

    return  $x  ;

}


function format_num($num){
    $x = number_format($num, 1,'.', '');

    if (fmod($x,1)==0)
        $x = number_format($num, 0, ',', '.');
    else
        $x = number_format($num, 1, ',', '.');

    return  $x  ;

}


function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
