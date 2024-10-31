<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{fonts}
	<style type="text/css">

		@page { margin: 10px 50px; }
		body p, body {
			overflow: hidden;
			padding: 5px 0px;
			word-break: normal;
			font-family: '{misc_body_font_family}';
			font-size: {misc_body_font_size} !important;
		}

		#details-left,
		#details-right {
			text-align: left;
			border: 2px solid #e3e3e3;
			border-radius: 10px;
			display: inline-block;
			width: 40%;
			padding: 20px
		}

		h1,h2 {
			font-family: '{misc_heading_font_family}' !important;
			font-size: {misc_heading_font_size};
			font-weight: {misc_heading_font_weight};
		}

		table .heading, th{
			font-family: '{table_heading_font_family}' !important;
			font-size: {table_heading_font_size};
			font-weight: {table_heading_font_weight};
			border-top: 1px solid #b5b5b5;
			border-bottom: 1px solid #b5b5b5;
		}

		thead th:first-child { 
			text-align: center;
		}

		tbody td:first-child { 
			text-align: left;
			max-width: 500px;
		}
		tbody td {
			overflow: hidden;
			padding: 10px 5px;
			word-break: normal;
			font-family: '{table_body_font_family}';
			font-size: {table_body_font_size} !important;
			text-align: center;
			border-bottom: 1px solid #e3e3e3;
			border-collapse: collapse;
		}

		.tg .woocommerce-Price-amount, .tg .woocommerce-Price-currencySymbol {
			font-size: {table_body_font_size} !important;
		}

		.text-left {
			text-align: left;
		}

		.text-center{
			text-align: center;
		}

		.text-top{
			vertical-align: top;
		}

		.tg {
			border-collapse: collapse;
			border-spacing: 0;
			margin: 0 auto;
		}
		{extra_styles}
	</style>
</head>

<body>

	<div id='wrapper'>
		
		<div style='text-align: center;'>
			{logo}
		</div>

		<div style='margin-top: 50px; text-align: center'>
			<!-- Visibility hidden is added in case the value does not exist; it will not break the details height-->
			<div id='details-left' style='margin-right: 10px'>
				{store_name}<br/>
				{store_address}<br/>
				{store_address_2}<br/>
				{store_city}<br/>
				{store_postcode}<br/>
				{store_phone_number}<span style='visibility: hidden'>0</span><br/>
			</div>
			<div id='details-right'>
				{customer_name}<br/>
				{customer_address_1}<br/>
				{customer_address_2}<span style='visibility: hidden'>0</span><br/>
				{customer_city}<br/>
				{customer_postcode}<br/>
				{customer_phone_number}<span style='visibility: hidden'>0</span><br/>
			</div>
		</div>

		<div style='margin-left: 0; margin-bottom: 10px; text-align: center'>
			<h2>{invoice_text}</h2>
			<br/>
			<br/>
			<div style='display: inline-block; margin-right: 50px; text-align: left !important;'>
				<p><span style='text-decoration: underline;'>{order_text}:</span> {order_number} </p>
				<p><span style='text-decoration: underline;'>{date_text}:</span> {date} </p>
			</div>

			<div style='display: inline-block; text-align: left !important;'>
				<p><span style='text-decoration: underline;'>{shipping_method_text}:</span> {order_shipping_method} </p>
				<p><span style='text-decoration: underline;'>{payment_method_text}:</span> {order_payment_method} </p>
			</div>
		</div>

			<table class="tg" style='width: 100%;'>
				<thead style='background: #e3e3e3;'>
					<tr>
						{table_headings}
					</tr>
				</thead>
				<tbody>
					{table_rows}
		</tbody>
	
			</table>
			<table style='margin-top: 100px; float: right; width: 50%; margin-right: 0; border-spacing: 0; page-break-inside: avoid;'>
				<thead style='background: #e3e3e3'>
					<tr>
						<th style='text-align: center' colspan='2'>{summary_text}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{subtotal_text}</td>
						<td style='text-align: right'>{order_subtotal}</td>
					</tr>
					<tr>
						<td>{tax_text}</td>
						<td style='text-align: right'>{order_tax}</td>
					</tr>
					{order_discount_markup_cumulus}
					<tr>
						<td>{shipping_text}</td>
						<td style='text-align: right'>{order_shipping}</td>
					</tr>
					<tr>
						<td>{order_total_text}</td>
						<td style='text-align: right'>{order_total}</td>
					</tr>
				</tbody>
			</table>
	</div>
	{extra}
	<?php
	do_action( 'printus_template__after_template_data' );
	?>
	<div style='vertical-align: bottom; height: 100px; width: 100%; margin-top: 180px; clear: both'>
	{order_note}
	{powered_by}
	</div>
</body>
</html>
