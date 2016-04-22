<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .grow { transition: all .2s ease-in-out; }

        body {
            font-family: 'Open Sans';

            background: white url("/extension/static/FoggyBaumwallAtNight.jpg") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
            font-size:0.8em;
        }

        main {
            top: 0px;
            left: 0px;
            margin: 30px;
            padding: 30px;
            width: 30em;
            word-wrap: break-word;
            color: white;
            background: black;
            opacity: 0.6;
            border-radius: 1em;
        }

        main:hover {
            opacity: 0.9;
            background-color:#372B18;
        }

        h1 {
            font-size: 2em;
            padding: 0.2em;
            border-bottom: 0.1em solid white;
            background-color: white;
            color:black;
            text-align:center;
            border-radius: 4em;
        }

        h2 {
            font-size: 1em;
            font-weight: normal;
            text-align: justify;
        }

        h3 {
            font-size: 1.2em;
            color: black;
            padding: 0.2em;
            background-color: white;
            border-radius: 0.5em;
            text-align: center;
            margin-top:2em;
        }

        h4
        {
            margin:0;
        }

        a
        {
            color: white;
            text-decoration: none;
        }

        li {
            padding-bottom: 1em;
        }

        li a:hover
        {
            text-shadow: 1px 2px 4px #9b9b9b;
            margin-left:0.5em;
        }


        footer
        {
            position: fixed;
            padding: 0.3em;
            bottom:0px;
            background:black;
            width: 100%;
            text-align: center;
            opacity: 0.4;
        }

        footer:hover
        {
            opacity: 0.8;

        }

        footer a
        {
            text-decoration: blink;
            font-weight: normal;
            display:inline-block;
        }

        footer a:hover
        {
            transform: scale(1.4);
        }

        #error pre {
            height: 300px;
            overflow: auto;
        }

    </style>
    <meta name="robots" content="noindex, follow">
</head>
<body>

<main>

    <h1>RPF - The RP²-Framework</h1>
    <h2>The RPF provides a ide-compatible PHP interface to the DomainFactory RP²-API for developers and collects a bunch
        of ready-to-use extensions for admins and customers. The system is modularly structured and can be extended
        easily.</h2>

    <h3>Extensions</h3>

    <ul class="fa-ul">
        <li> <i class="fa-li fa fa-file" aria-hidden="true"></i>
            <a href="csvExportDomain"><h4>CSV Export: Domain</h4>
            Export all domains with customer, order and settings</a>
        </li>
        <li> <i class="fa-li fa fa-file" aria-hidden="true"></i>
            <a href="csvExportMysql"><h4>CSV Export: MySQL</h4>
            Export all databases with customer, order and settings</a>
        </li>
    </ul>

    <h3>APIs</h3>

    <ul class="fa-ul">
        <li> <i class="fa-li fa fa-book" aria-hidden="true"></i>
            <a href="http://adoebeling.github.io/RP2-Framework/" target="_blank"><h4>Documentation</h4>
            ApiGen-Documentation of the interface</a>
        </li>
        <li> <i class="fa-li fa fa-code" aria-hidden="true"></i>
            <a href="https://github.com/ADoebeling/RP2-Framework/tree/master/htdocs/examples" target="_blank"><h4>Examples</h4>
            Some examples how the api works</a>
        </li>
    </ul>

    <h3>Info</h3>

    <ul class="fa-ul">
        <li> <i class="fa-li fa fa-github" aria-hidden="true"></i>
            <a href="https://github.com/ADoebeling/RP2-Framework" target="_blank"><h4>GitHub-Project</h4>
            Documentation, SourceCode and Issues</a>
        </li>
    </ul>

    </main>

    <footer>

    <i class="fa fa-code" aria-hidden="true"></i> Andreas Döbeling &nbsp;
    <a href="https://github.com/ADoebeling" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a> &nbsp;
    <a href="http://xing.doebeling.de" target="_blank"><i class="fa fa-xing" aria-hidden="true"></i></a> &nbsp;
    <a href="http://facebook.doebeling.de" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> &nbsp;
    <a href="mailto:ad@1601.com?subject=[RPF] Support/Feedback"><i class="fa fa-mail" aria-hidden="true"></i></a>

        &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;

        © 1601.communication gmbh &nbsp;
        <a href="https://www.1601.com/hosting" target="_blank"><i class="fa fa-chrome" aria-hidden="true"></i></a> &nbsp;
        <a href="https://github.com/1601com" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a> &nbsp;
        <a href="https://www.facebook.com/1601com" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>

        &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;

        <a href="https://www.flickr.com/photos/malte_s/5241688891/" target="_blank"><i class="fa fa-photo" aria-hidden="true"></i></a> &nbsp; Malte Sörensen
    </footer>


</body>

</html>