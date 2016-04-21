<head>
    <style>
        body
        {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        #errorLayer
        {
            background: white url("/extension/static/sadMumby.jpg")no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            z-index: 20;
            height: 100%;
            width: 100%;
            background-repeat:no-repeat;
            background-position:center;
            position:absolute;
            top: 0px;
            left: 0px;
        }

        #error
        {
            position: absolute;
            top: 0px;
            left: 0px;
            margin: 30px;
            padding: 30px;
            width: 350px;
            word-wrap:break-word;
            color: white;
            background: black;
            opacity: 0.5;
        }

        #error h1
        {
            font-size: 1.5em;
            margin-top:0;
        }

        #error pre
        {
            height: 300px;
            overflow: auto;
        }


    </style>
</head>
<body>
<div id="errorLayer">
    <div id="error">
        <h1><?=$title?></h1>
        <?=$text?>
    </div>
</div>
</body>