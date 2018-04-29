<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>@page { size: A5 portrait }</style>

  <!-- Custom styles for this document -->
  <link href='https://fonts.googleapis.com/css?family=Tangerine:700' rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Allura" rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Vast+Shadow" rel='stylesheet' type='text/css'>
  <style>
  body{
    padding: 5mm;
    font-family: "Helvetica Neue",Arial,sans-serif;
  }
    /** For screen preview **/
    .sheet.padding-05mm { padding: 05mm }
    .sheet.padding-10mm { padding: 10mm }
    .sheet.padding-15mm { padding: 15mm }
    .sheet.padding-20mm { padding: 20mm }
    .sheet.padding-25mm { padding: 25mm }
    .sheet.padding-0mm { padding: 0mm }
    
    article { 
      border: 4px double transparent;
      border-radius: 2mm; 
      width: 125mm; 
      height: 175mm;
      margin: 0mm;
      padding: 5mm;
      font-family: "Helvetica Neue",Arial,sans-serif;
    }
    .logo{margin: 3mm 0; }
    .title{
      font-size: 20pt; 
      margin-bottom: 5mm; 
      color: #17a2b8;
    }
    .certificate-number{
      font-family: courier;
    }
    
    p { 
      margin-bottom: 4mm;
      font-family: "Helvetica Neue",Arial,sans-serif;
    }
    .name{
      font-family: 'Tangerine'; 
      font-size: 45pt; 
      padding: 3mm; 
      margin:5mm 30mm 4mm 30mm; 
      border-bottom: 1pt dashed grey;
    }
    ul{list-style-type: none; margin-bottom: 0; margin-left:0;}
    
    .author-name{
      font-size: 18px;
      line-height: 4mm;
      margin-top:0;
      padding-top:0;
    }

    table { font-size: 75%; table-layout: fixed; width: 100%; }
    table { border-collapse: separate; border-spacing: 2px; }
    th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
    th, td { border-radius: 0.25em; border-style: solid; }
    th { background: #EEE; border-color: #BBB; }
    td { border-color: #DDD; }
    
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A5 portrait">
  <section class="sheetx padding-0mm">
    
    <article>
      <h1 style="display:block;background:gray;color:white;margin-bottom:0mm;">Receipt</h1>
      <div style="font-size:6pt;color:gray;margin-top:0;">
        <!--
        <img src="{{public_path()}}/img/logo.png" width="40" class="logo"  style="margin-bottom:0;float:right;"/>
        -->
        <img src="{{public_path()}}/img/{{basename(config('site_settings.site_logo'))}}" width="100" class="logo"  style="margin-bottom:0;float:right;"/>
				<p style="font-size:10pt; line-height:10pts;">
				  {{config('site_settings.site_name')}}<br>
				  {!! config('site_settings.receipt_address') !!}
				 
			  </p>
			</div>
			<div>
  			<table class="meta" style="float: right; width: 60%; font-size:9pt;">
  			  <tr>
  					<th><span>Receipt#</span></th>
  					<td class="certificate-number"><span>000000{{$payment->id}}</span></td>
  				</tr>
  				<tr>
  					<th><span>Date</span></th>
  					<td><span >{{$payment->created_at->format('F d, Y')}}</span></td>
  				</tr>
  				<tr>
  					<th><span>Amount Paid</span></th>
  					<td><span>{{Gabs::currency($payment->amount)}}</span></td>
  				</tr>
  			</table>
			</div>
			
			<div>
        <table class="inventory" style="width: 100%; margin-top:30mm;" >
  				<thead>
  					<tr>
  					  <th style="width:75%;"><span>Course</span></th>
  						<th style="width:25%;"><span>Price</span></th>
  					</tr>
  				</thead>
  				<tbody>
  				  <tr>
  				    <td>{{$payment->course->title}} - {{$payment->course->subtitle}}</td>
  				    <td>{{Gabs::currency($payment->amount)}}</td>
  				  </tr>
  				</tbody>
  			</table>
  			<p style="font-size:12pt;">Paid By: <b>{{$payment->user->name}}</b></p>
			</div>
      
    </article>
    
  
  </section>

</body>

</html>
