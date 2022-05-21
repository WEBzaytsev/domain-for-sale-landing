<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');


switch(filter_input_post_or_get('method_call', FILTER_SANITIZE_STRING)) {
    case 'contactForm':
        $name = filter_input_post_or_get('name', FILTER_SANITIZE_STRING);
        $email = filter_input_post_or_get('email', FILTER_SANITIZE_STRING);

        if(empty($name)) {
            returnOutError( 'Представьтесь пожалуйста.' );
        }
        if(empty($email)) {
            returnOutError( 'Укажите контактную почту.' );
        }
        

        $textExportEmail = '';
        $textExportEmail .= 'Имя: ' . $name . "\r\n";
        $textExportEmail .= 'Email: ' . $email . "\r\n";

        $ToEmail = "vovabezrukov47@gmail.com";
        $ToEmail = trim($ToEmail);
     
        $subject = 'Форма заявки';
        $headers = 'From: '. $ToEmail . "\r\n" .
            'Reply-To: ' . $ToEmail . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        if(mail($ToEmail, $subject, $textExportEmail, $headers)) {
            returnOut([
                'true' => true,
                'error' => false,
                'message' => 'Заявка принята.',
                'formReset' => true
            ]);	
        } else {
            returnOutError('Ошибка принятия заявки.');
        }
      //  filter_input_post_or_get
        returnOutError( 'В разработке.' );
    break;
    default:
        returnOutError('Метод не определён');
}

function filter_input_post_or_get($var_name, $filter = FILTER_DEFAULT, $options = 0) {
    return filter_input(
        $_SERVER['REQUEST_METHOD'] === 'POST' ? INPUT_POST : INPUT_GET,
            $var_name, $filter, $options);
}
function filter_input_array_post_or_get($options = FILTER_DEFAULT, $add_empty = true) {
    return filter_input_array(
        $_SERVER['REQUEST_METHOD'] === 'POST' ? INPUT_POST : INPUT_GET, $options, $add_empty);
}


// > Стандартная функция вывода ответа API
function returnOut(array $result) {
	echo json_encode($result); 
	exit; // Выходим из работы скрипта.
};

function returnOutError() {
	returnOut([
		'error' => true,
		'message' => print_r(func_num_args() == 1 ? current(func_get_args()) : func_get_args(), true)
	]);	
}

function returnOutSession() {
	returnOut([
		'error' => false,
		'message' => print_r(func_num_args() == 1 ? current(func_get_args()) : func_get_args(), true)
	]);	
}


?>