<?php
$files = array_merge(
    glob('e:/laragon/www/sindesa/resources/views/kades/**/*.blade.php'),
    glob('e:/laragon/www/sindesa/resources/views/kades/*.blade.php')
);
foreach($files as $f) {
    $c = file_get_contents($f);
    
    // Replace isset($countPerluTtd)
    $c = preg_replace(
        '/@if\(isset\(\$countPerluTtd\) && \$countPerluTtd > 0\)\s*<span([^>]+)>\{\{\s*\$countPerluTtd\s*\}\}<\/span>\s*@endif/',
        '@if(isset($unreadCountKades) && $unreadCountKades > 0) <span$1>{{ $unreadCountKades }}</span> @endif',
        $c
    );
    
    // Replace direct $countPerluTtd > 0
    $c = preg_replace(
        '/@if\(\$countPerluTtd > 0\)\s*<span([^>]+)>\{\{\s*\$countPerluTtd\s*\}\}<\/span>\s*@endif/',
        '@if(isset($unreadCountKades) && $unreadCountKades > 0) <span$1>{{ $unreadCountKades }}</span> @endif',
        $c
    );

    file_put_contents($f, $c);
    echo "Processed $f\n";
}
