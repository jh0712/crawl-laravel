<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style type="text/css">
            /* 在您的CSS文件中添加這些樣式 */

            /* 表格外容器 */
            .py-12 {
                padding: 20px;
            }

            /* 表格最大寬度和居中 */
            .max-w-7xl {
                max-width: 100%;
                margin: 0 auto;
            }

            /* 表格樣式 */
            .crawl_datatable {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                background-color: #fff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            /* 表頭樣式 */
            .crawl_datatable th {
                background-color: #f3f4f6;
                padding: 12px;
                text-align: left;
                border-bottom: 2px solid #e5e7eb;
                font-weight: bold;
            }

            /* 表身樣式（奇數行） */
            .crawl_datatable tbody tr:nth-child(odd) {
                background-color: #f7fafc;
            }

            /* 表身樣式（偶數行） */
            .crawl_datatable tbody tr:nth-child(even) {
                background-color: #fff;
            }

            /* 表格單元格樣式 */
            .crawl_datatable td {
                padding: 12px;
                border-bottom: 1px solid #e5e7eb;
            }

            /* 鼠標懸停樣式 */
            .crawl_datatable tbody tr:hover {
                background-color: #d1e5f0;
            }
            /* 通用的 CSS 样式 */
            .button {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                background-color: #007bff;
                color: #ffffff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .button:hover {
                background-color: #0056b3;
            }
            .spacer {
                margin-left: 20px; /* 调整左边距以增加按钮和文本之间的空间 */
                margin-right: 20px; /* 调整左边距以增加按钮和文本之间的空间 */
            }
        </style>

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
