<?php
    if (!defined('IN_SITE')) die ('The request not found');
	// Hàm tạo URL
    function base_url($uri = ''){
        return 'http://localhost:8081/st1/'.$uri;
    }
     
    // Hàm redirect
    function redirect($url){
        header("Location:{$url}");
        exit();
    }
     
    // Hàm lấy value từ $_POST
    function input_post($key){
        return isset($_POST[$key]) ? trim($_POST[$key]) : false;
    }
     
    // Hàm lấy value từ $_GET
    function input_get($key){
        return isset($_GET[$key]) ? trim($_GET[$key]) : false;
    }
     
    // Hàm kiểm tra submit
    function is_submit($key){
        return (isset($_POST['request_name']) && $_POST['request_name'] == $key);
    }
     
    // Hàm show error
    function show_error($error, $key){
        echo '<span style="color: red">'.(empty($error[$key]) ? "" : $error[$key]). '</span>';
    }
    
    // Tạo chuỗi query string
    function create_link($uri, $filter = array()){
        $string = '';
        foreach ($filter as $key => $val){
            if ($val != ''){
                $string .= "&{$key}={$val}";
            }
        }
        return $uri . ($string ? '?'.ltrim($string, '&') : '');
    }
    
    
    // Hàm phân trang
    function paging($link, $total_records, $current_page, $limit)
    {    
        // Tính tổng số trang
        $total_page = ceil($total_records / $limit);
         
        // Giới hạn current_page trong khoảng 1 đến total_page
        if ($current_page > $total_page){
            $current_page = $total_page;
        }
        else if ($current_page < 1){
            $current_page = 1;
        }
         
        // Tìm Start
        $start = ($current_page - 1) * $limit;
     
        $html = '';
         
        // nếu current_page > 1 và total_page > 1 mới hiển thị nút prev
        if ($current_page > 1 && $total_page > 1){
            $html .= '<a href="'.str_replace('{page}', $current_page-1, $link).'">Prev</a>';
        }
     
        // Lặp khoảng giữa
        for ($i = 1; $i <= $total_page; $i++){
            // Nếu là trang hiện tại thì hiển thị thẻ span
            // ngược lại hiển thị thẻ a
            if ($i == $current_page){
                $html .= '<span>'.$i.'</span>';
            }
            else{
                $html .= '<a href="'.str_replace('{page}', $i, $link).'">'.$i.'</a>';
            }
        }
     
        // nếu current_page < $total_page và total_page > 1 mới hiển thị nút prev
        if ($current_page < $total_page && $total_page > 1){
            $html .= '<a href="'.str_replace('{page}', $current_page+1, $link).'">Next</a>';
        }
         
        // Trả kết quả
        return array(
            'start' => $start,
            'limit' => $limit,
            'html' => $html
        );
    }
?>