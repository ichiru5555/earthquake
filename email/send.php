<?php
mb_language("Japanese"); 
mb_internal_encoding("UTF-8");

/*
P2P地震情報を使用しています。
地震が来たらメールを送信するプログラムです。
*/
//受信するメールアドレス
$to = '';

$earthquake_value = '0';

$url = 'https://api.p2pquake.net/v2/jma/quake';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$result = json_decode($response, true);

$prefectures = null;
$earthquake_intensity = null;

//変数は日本語を英語に翻訳したものなので長いです.
//ID.
$id = $result[$earthquake_value]['id'];
//受信時間.
$reception_time = $result[$earthquake_value]['time'];
//発表元.
$Publisher = $result[$earthquake_value]['issue']['source'];
//発表時間.
$Announcement_time = $result[$earthquake_value]['issue']['time'];
//発表のタイプ.
//これはifで文字に変換します.
$Presentation_type = $result[$earthquake_value]['issue']['type'];
//訂正の有無.
//これはifで変換します.
$modification = $result[$earthquake_value]['issue']['correct'];
//地震発生時間.
$earthquake_time = $result[$earthquake_value]['earthquake']['time'];
//地震名所.
$earthquake_name = $result[$earthquake_value]['earthquake']['hypocenter']['name'];
//地震の緯度
$latitude = $result[$earthquake_value]['earthquake']['hypocenter']['longitude'];
//地震の深さ
$depth = $result[$earthquake_value]['earthquake']['hypocenter']['depth'];
//マグニチュード
$magnitude = $result[$earthquake_value]['earthquake']['hypocenter']['magnitude'];
//マックス地震震度
//ifで文字に変換
$earthquake_intensity_max = $result[$earthquake_value]['earthquake']['maxScale'];
//地震の津波
//ifで文字変換
$tsunami = $result[$earthquake_value]['earthquake']['domesticTsunami'];
//震度観測の情報
//都道府県
$prefectures = @$result[$earthquake_value]['points'][$earthquake_value]['pref'];
//震度
//ifで文字に変換
$earthquake_intensity = @$result[$earthquake_value]['points'][$earthquake_value]['scale'];

//ifで文字変換の処理

if($Presentation_type == 'ScalePrompt'){
    $Presentation_type = '震度速報';
}elseif($Presentation_type == 'Destination'){
    $Presentation_type = '震源に関する情報';
}elseif($Presentation_type == 'ScaleAndDestination'){
    $Presentation_type = '震度・震源に関する情報';
}elseif($Presentation_type == 'DetailScale'){
    $Presentation_type = '各地の震度に関する情報';
}elseif($Presentation_type == 'Foreign'){
    $Presentation_type = '遠地地震に関する情報';
}elseif($Presentation_type == 'Other'){
    $Presentation_type = 'その他の情報';
}

if($modification == 'None'){
    $modification = 'なし';
}elseif($modification == 'Unknown'){
    $modification = '不明';
}elseif($modification == 'ScaleOnly'){
    $modification = '震度';
}elseif($modification == 'DestinationOnly'){
    $modification = '震源';
}elseif($modification == 'ScaleAndDestination'){
    $modification = '震度・震源';
}

if($earthquake_intensity_max == '-1'){
    $earthquake_intensity_max = '震度情報なし';
}elseif($earthquake_intensity_max == '10'){
    $earthquake_intensity_max = '震度1';
}elseif($earthquake_intensity_max == '20'){
    $earthquake_intensity_max = '震度2';
}elseif($earthquake_intensity_max == '30'){
    $earthquake_intensity_max = '震度3';
}elseif($earthquake_intensity_max == '40'){
    $earthquake_intensity_max = '震度4';
}elseif($earthquake_intensity_max == '45'){
    $earthquake_intensity_max = '震度5弱';
}elseif($earthquake_intensity_max == '50'){
    $earthquake_intensity_max = '震度5強';
}elseif($earthquake_intensity_max == '55'){
    $earthquake_intensity_max = '震度6弱';
}elseif($earthquake_intensity_max == '60'){
    $earthquake_intensity_max = '震度6強';
}elseif($earthquake_intensity_max == '70'){
    $earthquake_intensity_max = '震度7';
}

if($tsunami == 'None'){
    $tsunami = 'なし';
}elseif($tsunami == 'Unknown'){
    $tsunami = '不明';
}elseif($tsunami == 'Checking'){
    $tsunami = '調査中';
}elseif($tsunami == 'NonEffective'){
    $tsunami = '若干の海面変動が予想されるが、被害の心配なし';
}elseif($tsunami == 'Watch'){
    $tsunami = '津波注意報';
}elseif($tsunami == 'Warning'){
    $tsunami = '津波予報(種類不明)';
}

if($earthquake_intensity == '10'){
    $earthquake_intensity = '震度1';
}elseif($earthquake_intensity == '20'){
    $earthquake_intensity = '震度2';
}elseif($earthquake_intensity == '30'){
    $earthquake_intensity = '震度3';
}elseif($earthquake_intensity == '40'){
    $earthquake_intensity = '震度4';
}elseif($earthquake_intensity == '45'){
    $earthquake_intensity = '震度5弱';
}elseif($earthquake_intensity == '50'){
    $earthquake_intensity = '震度5強';
}elseif($earthquake_intensity == '55'){
    $earthquake_intensity = '震度6弱';
}elseif($earthquake_intensity == '60'){
    $earthquake_intensity = '震度6強';
}elseif($earthquake_intensity == '70'){
    $earthquake_intensity = '震度7';
}

//メール送信

$date_time = date("Y年m月d日 H時i分s秒");
$contents_mail = <<< END
地震が発生したためメールを送信しました。
地震受信時間: $reception_time
地震発表元: $Publisher
地震発表時間: $Announcement_time
地震の発生時間: $earthquake_time
地震発生場所: $earthquake_name
震度: $earthquake_intensity
最大震度: $earthquake_intensity_max
津波: $tsunami
緯度: $latitude
マグニチュード: $magnitude
観測場所: $prefectures
メール送信時間$date_time
END;

$headers = [
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=ISO-2022-JP',
    'From' => '',
];

array_walk( $headers, function( $_val, $_key ) use ( &$header_str ) {
    $header_str .= sprintf( "%s: %s \r\n", trim( $_key ), trim( $_val ) );
} );
mb_send_mail($to, $Presentation_type, $contents_mail, $header_str);
