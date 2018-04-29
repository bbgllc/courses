<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Receipt</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>@page { size: A4 landscape }</style>

  <!-- Custom styles for this document -->
  <link href='https://fonts.googleapis.com/css?family=Tangerine:700' rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Allura" rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Vast+Shadow" rel='stylesheet' type='text/css'>
  <style>
  body{
    padding: 1mm;
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
      border: 0px double #17a2b8;
      padding: 5mm 0 0 0; 
      border-radius: 2mm; 
      width: 296mm;
      height: 200mm;
      margin: 0mm;
      text-align: center;
      font-family: "Helvetica Neue",Arial,sans-serif;
      background: url(https://udemy-clone-fgneba.c9users.io/img/cert2.jpg) repeat;
      background-size: cover;
      background-position: center center;
    }
    .logo{margin: 3mm 0; }
    
    
    p { 
      line-height: 8mm; margin-bottom: 4mm; margin-top:5mm; font-size: 18pt;
      font-family: "Helvetica Neue",Arial,sans-serif;
      padding-left: 10mm;
      padding-right:10mm;
    }
    .name{
      font-family: 'Tangerine'; 
      font-size: 45pt; 
      padding: 3mm; 
      margin:5mm 40mm 4mm 40mm; 
      border-bottom: 1pt dashed grey;
    }
    ul{list-style-type: none; margin-bottom: 0;}
    .author-signature{
      font-family: 'Tangerine'; 
      font-size: 28pt;
      margin: 2mm 80mm;
      padding-bottom: 0;
      line-height: 10mm;
      border-bottom: 1px solid gray;
      /*text-decoration: underline;*/
      font-family: 'Allura', cursive;
      color: blue;
    }
    .author-name{
      font-size: 18px;
      line-height: 4mm;
      margin-top:0;
      padding-top:0;
    }
    .title{
      font-size: 35pt; 
      margin-bottom: 5mm; 
      font-family: 'Vast Shadow', cursive !important;
      color: #17a2b8;
    }
    .and{ font-family: 'Tangerine'; font-size: 30pt;  }
    .cert-div{
      margin-top: -5mm;
    }
    .certificate-number{
      font-size: 13pt;
      font-family: courier;
      line-height: 1mm;
    }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 landscape">
  <section class="sheetx padding-0mm">
    
    <article>
      <img src="{{public_path()}}/img/{{basename(config('site_settings.site_logo'))}}" 
        style="margin-top:15mm;" width="120" class="logo"  />
      <h1 style="margin-top:-2mm; padding-top:5mm;" class="title">Certificate of Completion</h1>
      <p>This is to certify that</p>
      <p class="name">{{ auth()->user()->name }}</p>
      <p style="margin-left: 25mm; margin-right: 25mm;">
        successfully completed the <b>"{{ $certificate->course->title }}"</b> course
        on {{$certificate->created_at->format('F d, Y')}} with the following details:
      </p>
      <ul>
        @if($certificate->video_hours > 0)
          <li>{{$certificate->video_hours}} hours of video content</li>
        @endif
        @if($certificate->total_articles > 0)
          <li>{{$certificate->total_articles}} articles</li>
        @endif
        @if($certificate->total_quizzes > 0)
          <li>{{$certificate->total_quizzes}} quizzes</li>
        @endif
      </ul>
      
      <div style="margin: 0 30mm">
        <p class="author-signature">
          {{$certificate->course->author->name}}
        </p>
        <p class="author-name">{{$certificate->course->author->name}}, course author</p>
        <p class="and">&</p>
        <p>{{config('site_settings.site_name')}}</p>
        
        <div class="cert-div">
          <p class="certificate-number" style="font-size:10pt;">
            Certificate No.: {{$certificate->certificate_no}}
          </p>
          <p class="certificate-number" style="font-size:10pt;">
            Verify at.: {{route('frontend.verify.certificate')}}
          </p>
        </div>
      </div>
    </article>
    
  
  </section>

</body>

</html>
