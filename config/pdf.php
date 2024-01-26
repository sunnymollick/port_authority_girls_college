<?php

return [
  'mode'               => 'utf-8',
  'format'             => 'A4',
  'defaultFontSize'    => '10',
  'defaultFont'        => 'sans-serif',
  'marginLeft'         => 5,
  'marginRight'        => 5,
  'marginTop'          => 2,
  'marginBottom'       => 5,
  'marginHeader'       => 0,
  'marginFooter'       => 0,
  'orientation'        => 'P',
  'title'              => 'PDF',
  'author'             => '',
  'watermark'          => 'CBMC',
  'showWatermark'      => false,
  'watermarkFont'      => 'sans-serif',
  'SetDisplayMode'     => 'fullpage',
  'watermarkTextAlpha' => 0.1,
	'tempDir'               => base_path('../temp/'),
  'font_path' => base_path('resources/fonts/'),
  'font_data' => [
    'bangla' => [
      'R'  => 'SolaimanLipi.ttf',
      'B'  => 'SolaimanLipi.ttf',
      'I'  => 'SolaimanLipi.ttf',
      'BI'  => 'SolaimanLipi.ttf',
			'useOTL' => 0xFF,
			'useKashida' => 75,
		]
	]
];
