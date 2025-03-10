<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="//code.jquery.com/jquery-1.11.3.js"></script>

    <title>Standalone Update</title>

  </head>
  <body>
  <style>
      #progress-bar-standalone-progress-wrapper {
          background: #fcf8f8;
          border: 1px solid #fcf8f8;
          border-radius: 10px;
          height: 13px;
          margin: 20px;
          width: 500px;
          overflow: hidden;
      }

      #progress-bar-standalone {
          width: 0%;
          height: 13px;
          background: #54b5fa;
          padding-left: 20px;
          color: white;
      }
  </style>
  <script type="text/javascript">
    $(document).ready(function() {

        $('.js-update-log').html('Loading...');

        // Check is started
        $.get("actions.php?isStarted=1&format=json", function(data) {
            if (data.started) {
                // If is started we want to read log file
                $.get("actions.php?getLogfile=1&format=json", function(data) {
                    readLog(data.logfile);
                });
            } else {
                // else not started we want to start the process
                $.get("actions.php?startSession=1&format=json&installVersion=<?php echo $_GET['installVersion']; ?>", function(data) {
                   // and now we can read log
                    $.get("actions.php?getLogfile=1&format=json", function(data) {
                        readLog(data.logfile);
                    });
                    // Start updating
                    setTimeout(function() {
                        execStartUpdating();
                    }, 800);
                });
            }
        });
    });

    downloadTimeoutOrErrorStartedFallback  = false;
    function execStartUpdating() {
        preventWindowClose = true;

        $.ajax({
                type: "GET",
                url: "actions.php?startUpdating=1&format=json",
                error: function(xhr, statusText) {
                    //wait for the backend  to be ready
                    setInterval(function () {
                       var html = $('.js-update-log').html();
                       //chk for text
                        //"has been downloaded successfully"
                        if (html.indexOf('has been downloaded successfully') !== -1) {
                            //if found then start unzip
                            if(!downloadTimeoutOrErrorStartedFallback) {
                                startUnzipAjaxOnSingleStep();
                                downloadTimeoutOrErrorStartedFallback = true;
                            }
                        }
                    }, 1000);

                },
                success: function(data){
                    if (data.updating.downloaded) {
                        startUnzipAjaxOnMultiSteps();
                    } else {
                        $('.js-update-log').html("Can't download the app.");
                    }
                }
            }
        );


    }

    function execCleanupStepAjax() {
          $.get('actions.php?replaceFilesExecCleanupStep=1&format=json', function(data) {

        });
    }

    function startUnzipAjaxOnSingleStep() {
        $.get("actions.php?unzippApp=1&format=json", function (data) {
            if (data.unzipping.unzipped) {
                $.get("actions.php?replaceFilesPrepareStepsNeeded=1&format=json", function (data) {
                    if (data.replace_steps.steps_needed) {
                        execReplaceStepsAjax(data.replace_steps.steps_needed)
                    } else {
                        $('.js-update-log').html("Can't prepare replace steps.");
                    }

                });
            } else {
                $('.js-update-log').html("Can't unzip the app.");
            }
        });

    }

    function startUnzipAjaxOnMultiSteps() {
        $.get("actions.php?unzippAppGetNumberOfStepsNeeded=1&format=json", function (data) {
            if (data.unzipping.unzip_steps_needed) {
                execUnzipChunkStepsAjax(data.unzipping.unzip_steps_needed)
            } else {
                $('.js-update-log').html("Cannot open the zip with updates file.");
            }
        });

    }



    function execUnzipChunkStepsAjax(numsteps, step) {
        if (typeof step === 'undefined') {
            step = 0;
        }

        if (step > numsteps) {
            return;
        }

        var number1 = step;
        var number2 = numsteps;

        var progressPrecent = (Math.floor((number1 / number2) * 100));
        //var bar = '<br><progress  value="'+ progressPrecent +'" max="100"> '+ progressPrecent +' </progress>'
        var bar = '<div id="progress-bar-standalone-progress-wrapper"><div style="width:'+progressPrecent+'%" id="progress-bar-standalone"></div></div>'



      //  $('.js-updating-the-software-text').html("Executing unzip step " + step + " of " + numsteps);
        $('.js-updating-the-software-text').html("Unzipping the app " + progressPrecent + "%" + bar);





        $.ajax({
            url: 'actions.php?unzipAppExecStep=' + step + '&format=json',
            type: 'GET',
            data: {
                format: 'json'
            },


            cache: false,

            success: function (data2) {
                if (data2 && data2.unzipping && data2.unzipping.unzip_step_executed) {
                    if (parseInt(data2.unzipping.unzip_step_executed) >= parseInt(numsteps)) {


                        setTimeout(function () {
                            $('.blob').fadeOut();
                            $('.js-updating-the-software-text').html('Unziping is done. Now we will replace files.');
                            $('.js-update-log').html("Preparing to replace files...");


                            $.get("actions.php?replaceFilesPrepareStepsNeeded=1&format=json", function (data) {
                                if (data.replace_steps.steps_needed) {
                                    execReplaceStepsAjax(data.replace_steps.steps_needed)
                                } else {
                                    $('.js-update-log').html("Can't prepare replace steps.");
                                }

                            });

                         }, 200);
                    }

                }
            },
            complete: function (data2) {
                // step++;
                // execUnzipChunkStepsAjax(numsteps, step)
            }
        }).done(function() {
            step++;
            execUnzipChunkStepsAjax(numsteps, step)
        });

    }



      function execReplaceStepsAjax(numsteps, step) {
        if (typeof step === 'undefined') {
            step = 0;
        }

        if (step > numsteps) {
            return;
        }

        //   for (let step = 0; step < numsteps; step++) {

          var number1 = step;
          var number2 = numsteps;

          var progressPrecent = (Math.floor((number1 / number2) * 100));


       // $('.js-updating-the-software-text').html("Executing replace step " + step + " of " + numsteps);
       //   var bar = '<br><progress  value="'+ progressPrecent +'" max="100"> '+ progressPrecent +' </progress>'
          var bar = '<div id="progress-bar-standalone-progress-wrapper"><div style="width:'+progressPrecent+'%" id="progress-bar-standalone"></div></div>'

          $('.js-updating-the-software-text').html("Replacing files " + progressPrecent + "%" + bar);

          $.ajax({
            url: 'actions.php?replaceFilesExecStep='+step+'&format=json',
            type: 'GET',

            data: {
                replaceFilesExecStep: step,
                format: 'json'
            },

            success: function (data2) {
                if (data2.replace_step_result.step_executed) {
                    if(parseInt(data2.replace_step_result.step_executed) >= parseInt(numsteps)) {

                    if(typeof readlogInterval !== 'undefined') {
                        clearInterval(readlogInterval);
                    }
                        // setTimeout(function () {
                        //   //  execCleanupStepAjax()
                        // }, 15000);
                     setTimeout(function () {
                         $('.blob').fadeOut();
                         preventWindowClose = false;
                         $('.js-updating-the-software-text').html('Done!');

                         var sound = new Audio("data:audio/wav;base64,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9v/0//T/8v/x//b/9P/3//T/+f/z//v/9P////j/BQDz/wgA9f8JAPT/DAD2/w4A8f8OAPX/CgD2/wsA9/8GAPj/BAD5//v/+P/5//j/9v/4//T/+P/0//f/9v/2//b/9f/6//L//P/z//3/9P////P/AADz/wUA8v8DAPH/BgDw/wYA7/8GAPD/AwDx/wEA8v8DAPD/AgD0/wIA9f////n//v/6/////P/8//3//f/8//z/+v/6//j/+f/1//j/9f/4//P/+f/z//n/8v/9//b//v/0/wEA9v8DAPb/AwD1/wMA9P8CAPT/AgDx////7//+//L//f/z//3/9f/7//j/+//6//3/+f/8//j/+//6//n/+//6//b/+//2//n/+P/6//f/+//0//3/8//8//P//P/0//7/8P/+/+///v/v//7/8v////P/AQDx/wIA9f8CAPj/AQD0/wAA9v/+//f//P/2//r/8//5//T/+P/z//b/9f/2//P/9f/2//f/9//3//f/+f/4//r/+P/+//3/AAD8/wEA+v8AAPj/AQD5//3/9f/7//T/+f/z//n/9v/3//X/+P/y//j/8P/5/+3/9//w//f/7v/4/+//+P/x//f/8//5//T/+//2//v/9//+//3//v/9//7//f/+//3//P/8//3//P/8//r//P/5//z/+f/6//f/+//2//v/9v/4//X/+P/0//j/9P/4//P/9v/2//n/9v/3//j/+//4//n/+P/8//n/+//5//r/+f/8//b/+v/2//r/9P/6//X//P/0//r/9v/8//f//f/0//3/9f/8//T//P/0//z/9P/6//P/+v/1//j/8//4//T/9//2//n/8//4//b/+P/0//r/9//6//f/+v/5//n/+f/6//j/+f/6//r/+//5//j/+//4//r/+P/7//n//P/5//z/+P/7//j/+//3//n/9f/5//X/9//1//r/9v/5//j/+P/3//r/9v/5//r/+//5//v/9//8//X//P/2//r/9v/9//f//P/2//z/+P/8//b/+f/2//j/+P/4//n/9v/6//j/+v/3//r/9//6//j/+//2//n/+f/4//r/+f/8//f/+//2//3/9P/7//j//P/1//v/9//7//b//P/5//v/+P/7//f/+//3//n/9//6//b/+P/2//j/9v/4//j/+P/5//n/+f/4//r/+v/7//v/+f/8//j//P/5//z/9//9//f//P/z//3/8//7//P/+//z//r/9f/6//P/+P/1//n/9v/5//f/+P/3//r/+v/5//3/+v/9//v//v/7/////f/9//3//f/8//v//f/8//v/+f/9//f/+v/3//3/9f/6//X/+//0//r/9P/6//P/+P/2//n/9f/4//b/+f/5//r/+P/5//r//P/5//v/+v/7//z//f/7//v/+v/9//z//P/6//3/+//7//n//P/5//r/+v/7//n/+v/3//r/+P/7//j/+v/3//v/9//6//f/+v/3//r/+P/6//j/+//6//r/+v/6//r/+//6//r/+f/8//v/+v/8//z/+v/9//v//P/6//z/+v/7//j/+//3//v/+P/5//j//P/4//r/9//7//n//P/3//v/+f/8//j//P/4//z/+v/9//n//P/5//3/+//8//3//P/+//v//v/7//7/+//+//r//v/8//v/+//7//z/+v/8//n//P/4//z/9//8//b/+//3//z/9v/8//f//P/5//3/+f/9//n//f/7//z/+//9//3/+//9//3//f/7//7//P/+//z//f/6//3//P/8//r/+//8//v//P/5//z/+f/9//n//f/4//3/+P/9//n//P/5//z/+v/7//j/+//8//v/+P/5//v/+//6//v//P/6//z//f/8//v//f/9//3//f/8//7//P/+//z////8////+/////v////7////+//+//z//v/8//3//f/9//z//P/9//v//P/7//3/+//8//r//P/8//z//P/6//z//P/9//r//v/8//3/+v////v//v/7/////P////z//v/9/////P/+//z//v/8//3/+//9//3//f/7//z//f/9//z//f/8//3//v/+//7//f/+////AAD9//7///////7//v////3//v/9//7/+//+//z//v/7//3/+//+//v//v/7//3//P/+//r//f/8//7//P/+//z//v/+/////f/+///////+//7//v///////v/+/////v/9//7////8//7//v/+//z//v/9//7/+//+//3////7//7//P////3////8//////////3////+//7//v///////v/+///////9//7///////7//v/+//7//v////7//v/+///////9//7///////3//////////f//////AAD+//7//v8AAP/////+/////v///////////////v/////////+//////////7//////////v8AAP///v/9/wAA///+//7////+//////////7////+/wAA//////7/AAD+//////////3/AAAAAP7//v8AAP///v///////v/////////+//////8AAP7//////////v8AAP///v///wAA/v///wAA///+/wAA/////wAA///+/wAAAAD/////AAD+/wAAAAD///7/AAAAAAAA////////AAD///////8AAP7//////wAA/v/////////+////AAAAAP7///8BAAAA/v///wAA//8AAAAA/////wAAAAD//wAAAAD//wAAAAD/////AAAAAP//AAAAAP////8AAAEA///+/wAAAQAAAP7/AAAAAAAA/////wAAAAD/////AQAAAP7/AAABAP////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP//AAABAAAA//8AAAAAAQAAAP////8BAAEA/////wEAAAAAAAAAAAD//wEAAAD/////AQD//wAAAAAAAP//AQAAAAAA//8BAAAAAAAAAAAA//8BAAEAAAD//wEAAAAAAAAAAQD//wAAAAABAAAAAAD//wAAAAABAAAA//8AAAEAAAD//wAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAP//AAABAAAAAAAAAAAAAAABAAEA/////wEAAQAAAP//AAAAAAEAAAAAAP//AAABAAAA//8AAAAAAAAAAAAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAQABAP//AAABAAAAAAABAAAA//8AAAIAAAD//wAAAQAAAAEAAQAAAP//AQABAAEA//8AAAEAAQAAAAAAAAAAAAAAAQAAAP//AQABAAAAAAAAAAAAAQABAAAA//8BAAEAAAAAAAEAAAAAAAEAAAD//wEAAgAAAP//AQABAAEAAAAAAAAAAQABAAAA//8AAAEAAQD/////AQABAP////8BAAAA//8AAAAAAAAAAAAA//8BAAEAAAD//wAAAAAAAP//AAAAAAAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAQAAAP//AAAAAAAAAAAAAP//AAABAAAA//8AAAAAAAAAAAAA/////wAAAQAAAP////8AAAEAAQD+////AgABAP////8BAAAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAQAAAP////8AAAAAAAD/////AAABAP////8AAAAA//8AAAAA/////wEAAAD/////AAAAAAAA/////wAAAAD/////AAAAAP////8AAAAA/////wAAAAD//wAAAAD/////AAAAAAAA/////wAAAAD/////AQAAAP////8AAAAAAAD/////AAABAP////8AAAAA//8AAAAAAAD/////AAAAAP////8AAAEA/////wAAAAD//wAAAAAAAP////8AAAEAAAD/////AAAAAAAA////////AAAAAAAA//8AAAAAAAAAAAAA/////wAAAQD/////AAAAAP//AQAAAP////8BAAEAAAD//wAAAAABAP////8AAAEAAAAAAAAAAAD//wEAAAD/////AQAAAP//AAABAP//AAAAAAAA//8AAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAAAAAAAAAAQAAAAAAAAABAAAAAAABAAAAAAAAAAEAAQAAAP//AAABAAEAAAD//wAAAQAAAP//AAAAAAAAAAABAAAA//8AAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD//wAAAQAAAP//AAABAAAA//8AAAAAAAAAAP//AAABAAAA//8AAAAAAAAAAAAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAQAAAP//AQABAP//AAABAAAA//8AAAAAAAAAAAAA//8AAAAAAAD//wAAAAAAAAAAAAAAAP//AAAAAAAAAAAAAP//AAABAAAA//8AAAEAAAD//wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP//AAAAAAAA//8AAAAAAAD//wAAAAAAAP//AAAAAAAA//8AAAAAAAAAAAAA//8AAAAAAAD//wAA/////wAAAQD/////AQABAP//AAAAAAAAAAABAP////8AAAEA/////wAAAQAAAP////8AAAAAAAD/////AAABAAAA/////wAAAQAAAP////8BAAAA/////wEA/////wEAAQD+////AQAAAP//AAAAAP//AAABAAAA//8AAAAAAAAAAP////8AAAAAAAD/////AAABAP///v8AAAEA//8AAAAA//8AAAIA///+/wAAAQD//wAAAAD//wAAAQD/////AQAAAP//AAABAAAA/////wEAAQD/////AQAAAP//AAABAP//AAABAAAA//8AAAEAAAD//wAAAQAAAP7/AQACAP///v8BAAEA//8AAAEA//8AAAEAAAD//wAAAAAAAAAA/////wEAAAD+/wAAAQAAAP//AAAAAAAAAAD//wAAAQAAAP//AAAAAAAAAQAAAP//AAAAAAAAAAAAAP//AAABAP////8BAAAA//8AAAAAAAAAAAAA//8AAAAAAAD//wAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAQAAAP////8AAAEAAAD/////AQAAAP//AAABAP////8AAAEA/////wAAAQAAAAAA//8AAAEAAAD//wAAAAAAAAAAAAD//wAAAAD//wAAAQAAAP//AAAAAAAAAAAAAP//AAAAAAAAAAD/////AAAAAAAAAAD/////AAABAP////8AAAAAAAAAAAAA//8AAAAAAAAAAAAAAAAAAAAAAAAAAAAA//8AAAAAAAD//wAAAAAAAP//AAAAAP////8BAAAA//8AAAEA/////wEAAQD+/wAAAQAAAAAAAABMSVNUTgAAAElORk9JQ1JECwAAADIwMTAtMTEtMDcAAElFTkcPAAAAdG9ueSBXaWxraW5zb24AAUlTRlQVAAAAU291bmQgRm9yZ2UgUHJvIDEwLjAAAkNEaWZEAAAARAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABDRGlmRAAAAEQAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQ0RpZkQAAABEAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==");
                         sound.play();

                         $('.js-update-log').html("You can visit your site now.");
                         $('.js-update-log').append("<br><br><a class='mw-standalone-text' href='../../../'>Continue</a>");
                      }, 1000);
                    }

                }
            },
            complete: function (data2) {
                // step++;
                //
                // execReplaceStepsAjax(numsteps, step)
                //
                // if(step >= numsteps) {
                //     setTimeout(function () {
                //         //    execCleanupStepAjax()
                //     }, 15000);
                // }
             }
        }).done(function() {
              var shouldClean = false;
              if(step >= numsteps) {
                  var shouldClean = true;
              }
              step++;

              execReplaceStepsAjax(numsteps, step)

              if(shouldClean) {
                  setTimeout(function () {
                        execCleanupStepAjax()
                  }, 1000);
              }
          });

        //  }
    }
    requestInProgress = false;
    function readLog(logfile)
    {
       readlogInterval = setInterval(function () {

           if(requestInProgress){
               return;
           }

            $.get(logfile, function (log) {
                try {
                    requestInProgress = true;
                    var jsonLogStatus = JSON.parse(log);
                    if (jsonLogStatus.success) {
                       $('.blob').fadeOut();
                        $('.js-updating-the-software-text').html('Done!');
                        preventWindowClose = false;
                        clearInterval(readlogInterval);
                        $('.js-update-log').html(jsonLogStatus.message);
                    }
                } catch(err) {
                    log = log.replace(/(?:\r\n|\r|\n)/g, '<br>');
                    $('.js-update-log').html(log);
                } finally {
                    requestInProgress = false; // Set the flag to indicate that the request is complete.
                }

            });
        }, 3000);
    }

    preventWindowClose = false;


    window.addEventListener('beforeunload', e => {
        if (!preventWindowClose) return;
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    });


  </script>


<style>
    .mw-standalone-loading {
        background-color:#4592ff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    svg {
        position: fixed;
    }

    .mw-standalone-text h4, h6, a.mw-standalone-text{
        color: #ffffff;
    }

    .blobs {
        filter: url(#goo);
        width: 300px;
        height: 150px;
        position: relative;
        overflow: hidden;
        border-radius: 70px;
        transform-style: preserve-3d;
    }
    .blobs .blob-center {
        transform-style: preserve-3d;
        position: absolute;
        background: #FFFFFF;
        top: 50%;
        left: 50%;
        width: 30px;
        height: 30px;
        transform-origin: left top;
        transform: scale(0.9) translate(-50%, -50%);
        -webkit-animation: blob-grow linear 3.4s infinite;
        animation: blob-grow linear 3.4s infinite;
        border-radius: 50%;
        box-shadow: 0 -10px 40px -5px #FFFFFF;
    }

    .blob {
        position: absolute;
        background: #FFFFFF;
        top: 50%;
        left: 50%;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        -webkit-animation: blobs ease-out 3.4s infinite;
        animation: blobs ease-out 3.4s infinite;
        transform: scale(0.9) translate(-50%, -50%);
        transform-origin: center top;
        opacity: 0;
    }
    .blob:nth-child(1) {
        -webkit-animation-delay: 0.2s;
        animation-delay: 0.2s;
    }
    .blob:nth-child(2) {
        -webkit-animation-delay: 0.4s;
        animation-delay: 0.4s;
    }
    .blob:nth-child(3) {
        -webkit-animation-delay: 0.6s;
        animation-delay: 0.6s;
    }
    .blob:nth-child(4) {
        -webkit-animation-delay: 0.8s;
        animation-delay: 0.8s;
    }
    .blob:nth-child(5) {
        -webkit-animation-delay: 1s;
        animation-delay: 1s;
    }

    @-webkit-keyframes blobs {
        0% {
            opacity: 0;
            transform: scale(0) translate(calc(-330px - 50%), -50%);
        }
        1% {
            opacity: 1;
        }
        35%, 65% {
            opacity: 1;
            transform: scale(0.9) translate(-50%, -50%);
        }
        99% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: scale(0) translate(calc(330px - 50%), -50%);
        }
    }

    @keyframes blobs {
        0% {
            opacity: 0;
            transform: scale(0) translate(calc(-330px - 50%), -50%);
        }
        1% {
            opacity: 1;
        }
        35%, 65% {
            opacity: 1;
            transform: scale(0.9) translate(-50%, -50%);
        }
        99% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: scale(0) translate(calc(330px - 50%), -50%);
        }
    }
    @-webkit-keyframes blob-grow {
        0%, 39% {
            transform: scale(0) translate(-50%, -50%);
        }
        40%, 42% {
            transform: scale(1, 0.9) translate(-50%, -50%);
        }
        43%, 44% {
            transform: scale(1.2, 1.1) translate(-50%, -50%);
        }
        45%, 46% {
            transform: scale(1.3, 1.2) translate(-50%, -50%);
        }
        47%, 48% {
            transform: scale(1.4, 1.3) translate(-50%, -50%);
        }
        52% {
            transform: scale(1.5, 1.4) translate(-50%, -50%);
        }
        54% {
            transform: scale(1.7, 1.6) translate(-50%, -50%);
        }
        58% {
            transform: scale(1.8, 1.7) translate(-50%, -50%);
        }
        68%, 70% {
            transform: scale(1.7, 1.5) translate(-50%, -50%);
        }
        78% {
            transform: scale(1.6, 1.4) translate(-50%, -50%);
        }
        80%, 81% {
            transform: scale(1.5, 1.4) translate(-50%, -50%);
        }
        82%, 83% {
            transform: scale(1.4, 1.3) translate(-50%, -50%);
        }
        84%, 85% {
            transform: scale(1.3, 1.2) translate(-50%, -50%);
        }
        86%, 87% {
            transform: scale(1.2, 1.1) translate(-50%, -50%);
        }
        90%, 91% {
            transform: scale(1, 0.9) translate(-50%, -50%);
        }
        92%, 100% {
            transform: scale(0) translate(-50%, -50%);
        }
    }
    @keyframes blob-grow {
        0%, 39% {
            transform: scale(0) translate(-50%, -50%);
        }
        40%, 42% {
            transform: scale(1, 0.9) translate(-50%, -50%);
        }
        43%, 44% {
            transform: scale(1.2, 1.1) translate(-50%, -50%);
        }
        45%, 46% {
            transform: scale(1.3, 1.2) translate(-50%, -50%);
        }
        47%, 48% {
            transform: scale(1.4, 1.3) translate(-50%, -50%);
        }
        52% {
            transform: scale(1.5, 1.4) translate(-50%, -50%);
        }
        54% {
            transform: scale(1.7, 1.6) translate(-50%, -50%);
        }
        58% {
            transform: scale(1.8, 1.7) translate(-50%, -50%);
        }
        68%, 70% {
            transform: scale(1.7, 1.5) translate(-50%, -50%);
        }
        78% {
            transform: scale(1.6, 1.4) translate(-50%, -50%);
        }
        80%, 81% {
            transform: scale(1.5, 1.4) translate(-50%, -50%);
        }
        82%, 83% {
            transform: scale(1.4, 1.3) translate(-50%, -50%);
        }
        84%, 85% {
            transform: scale(1.3, 1.2) translate(-50%, -50%);
        }
        86%, 87% {
            transform: scale(1.2, 1.1) translate(-50%, -50%);
        }
        90%, 91% {
            transform: scale(1, 0.9) translate(-50%, -50%);
        }
        92%, 100% {
            transform: scale(0) translate(-50%, -50%);
        }
    }
</style>

  <style>
      progress {
          border: solid 2px #ece9e9;

          margin-top:10px;
          margin-bottom:10px;
      }



      /*progress::before,*/
      /*progress::after {*/
      /*    content: "";*/
      /*    position: absolute;*/
      /*    top: 0;*/
      /*    left: 0;*/
      /*    height: 100%;*/
      /*}*/

      /*progress::before {*/
      /*    width: 100%;*/
      /*    background: #ece9e9;*/
      /*}*/

      /*progress::after {*/
      /*    width: 75%;*/
      /*    background: #23ab1e;*/
      /*}*/
  </style>
<div class="mw-standalone-loading">
    <div class="row flex-column align-items-center">
        <div class="blobs">
            <div class="blob-center"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
            <defs>
                <filter id="goo">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                    <feBlend in="SourceGraphic" in2="goo" />
                </filter>
            </defs>
        </svg>
        <div class="mw-standalone-text p-5 text-center">
            <h4 class="mb-2 js-updating-the-software-text">Updating the software</h4>
            <h6 class="js-update-log">Loading...</h6>
        </div>
    </div>
</div>

  </body>
</html>
