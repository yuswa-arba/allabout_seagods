<?php

function new_subscribe_template($title, $body)
{
    $template = '
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title></title>
            <style>
                .block-img img {
                    width: 100% !important;
                }
            </style>
        </head>
        <body style="font-family: Helvetica, Arial, sans;color: #494949;
            font-size: 12px;background-color: #f3f3f3;margin: 0px;height: 100%">
        <!--Header-->
        <div style="display: flex;align-items: center;padding: 15px 45px;background-color: #242424;">
            <div style="width: 50%;">
                <img src="http://seagodswetsuit.com/new/web/images/logo.png" alt="" height="auto">
            </div>
            <div style="width: 50%;float: right;text-align: right;">
                <p style="margin:0;text-align:right;color: #d6d6d6;font-size: 19px;"><b>Blog</b></p>
            </div>
        </div>
        <!--End Header-->
        
        
        <!--Body-->
        <div style="width: 60%;margin: 0 auto;background-color: #fff;padding: 20px 28px 45px;border-left: 1px solid #f5f5f5;border-right: 1px solid #f5f5f5;">
            <h2 style="color: #000;">' . $title . '</h2>
            <div class="block-img" style="width:100%;height: auto;max-width: 100%;overflow: auto;">
            ' . $body . '
            </div>
        </div>
        <!--End Body-->
        
        
        <!--Footer-->
        <div style="width: 60%;margin: 30px auto 0; padding: 20px 0 40px;border-top: 1px solid #eaeaea;text-align: center;">
            © 2019. Sea Gods Wetsuit
        </div>
        <!--End Footer-->
        
        </body>
        </html>';
    return $template;
}