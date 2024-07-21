<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminMenuController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getMenus()
    {

        $about_us = \App\AboutUsPage::withDescription()->first()->page_title ?? 'STORY';
        $about_us_values = \App\AboutValuesPage::withDescription()->first()->page_title ?? 'VALUES';
        $about_us_process = \App\AboutProcessPage::withDescription()->first()->page_title ??  'PROCESS';
        $products = \App\ProductsPage::withDescription()->first()->page_title ?? 'PRODUCTS';
        $news = \App\NewsPage::withDescription()->first()->page_title ?? 'NEWS';
        $certificates = \App\CertificatePage::withDescription()->first()->page_title ?? 'CERTIFICATES';
        $contact = \App\ContactPage::withDescription()->first()->page_title ?? 'CONTACT';


        return 
        [
            [
                'section' => [
                    'header' => 'HEADER'
                ]
            ],
            [
                'section' => [
                    'home_page' => 'HOME'
                ]
            ],
            [
                'section' => [
                    'about_us_page' => $about_us,
                ]
            ],
            [
                'section' => [
                    'about_values_page' => $about_us_values
                ]
            ],
            [
                'name' => $about_us_process,
                'section' => [
                    'about_process_page' => 'Page',
                    'about_process' => 'Records'
                ]
            ],
            [
                'name' => $products,
                'section' => [
                    'products_page' => 'Page',
                    'products_category' => 'Records'
                ]
            ],
            [
                'name' => $news,
                'section' => [
                    'news_page' => 'Page',
                    'news' => 'Records'
                ]
            ],
            [
                'name' => $certificates,
                'section' => [
                    'certificate_page' => 'Page',
                    'certificates' => 'Records'
                ]
            ],
            [
                'section' => [
                    'contact_page' => $contact
                ]
            ],
            [
                'name' => 'Language',
                'section' => [
                    'language' => 'LANGUAGE'
                ]
            ]            
        ];
    }
    
}
