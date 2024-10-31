<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{fonts}
	<style type="text/css">
		{nimbus_page_margins}
		body p, li, span{
			font-family: '{misc_body_font_family}';
			font-size: {misc_body_font_size} !important;
			list-style-type: none; 
		}

		h1,h2 {
			font-family: '{misc_heading_font_family}' !important;
			font-size: {misc_heading_font_size};
			font-weight: {misc_heading_font_weight};
		}

		.tg {
			border: none;
			border-collapse: collapse;
			border-spacing: 0;
			margin: 20px auto !important;
		}

		.tg td {
			border-style: solid;
			border-width: 0;
			overflow: hidden;
			padding: 10px 5px;
			word-break: normal;
			font-family: '{table_body_font_family}' !important;
			font-size: {table_body_font_size};
			font-weight: {table_body_font_weight};
		}

		.tg .woocommerce-Price-amount, .tg .woocommerce-Price-currencySymbol {
			font-size: {table_body_font_size} !important;
		}

		.summary{
			font-family: '{table_body_font_family}' !important;
			font-size: {table_body_font_size};
			font-weight: {table_body_font_weight};
		}

		table .heading, th{
			font-family: '{table_heading_font_family}' !important;
			font-size: {table_heading_font_size};
			font-weight: {table_heading_font_weight};
		}

		.text-bold {
			font-weight: {body_font_weight};
		}

		.text-left {
			text-align: left;
		}

		.text-right {
			text-align: right;
		}

		.text-center {
			text-align: center;
		}

		.text-bottom {
			vertical-align: bottom;
		}

		.text-top {
			vertical-align: top;
		}
		{extra_styles}
	</style>
</head>

<body>
	<div style='width: 100px; margin: 0 auto; text-align: center; padding-top: 5px'>{logo}</div>
	<div style='width: 100%; margin: 0 auto; page-break-after: avoid'>
		<div style='text-align: center; page-break-after: avoid'>
				<h1>{store_name}</h1>
				<li>{customer_name}</li>
				<li>{customer_address_1}</li>
				<li>{customer_address_2}</li>
				<li>{customer_city}</li>
				<li>{customer_postcode}</li>
				<li>{customer_phone_number}</li>
				<li>{date}</li>
				<li>{time_now}&nbsp;&nbsp;{order_number}</li>
		</div>
		<table class="tg" style="width: 100%; page-break-after: avoid">
			<tbody style="width: 100%;">
				<thead>
					<tr>
						{table_headings}
					</tr>
				</thead>
				{table_rows}
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="text-bold text-left"><span class='heading'>{subtotal_text}</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-bold text-center"><span class='summary'>{order_subtotal}</span></td>
				</tr>
				<tr>
					<td class="text-bold text-left"><span class='heading'>{shipping_text}</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-bold text-center"><span class='summary'>{order_shipping}</span></td>
				</tr>
				<tr>
					<td class="text-bold text-left"><span class='heading'>{tax_text}</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-bold text-center"><span class='summary'>{order_tax}</span></td>
				</tr>
				{order_discount_markup_nimbus}
				<tr>
					<td class="text-bold text-left"><span class='heading'>{order_total_text}</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-bold text-center"><span class='summary'>{order_total}</span></td>
				</tr>
			</tbody>
		</table>
	</div>
	{extra}
	<div style='width: 100%; margin: 0 auto; text-align: center'>
		{order_note}
	<?php
	do_action( 'printus_template__after_template_data' );
	?>
	</div>
	{powered_by}
</body>

</html>
