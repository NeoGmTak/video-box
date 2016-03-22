
<!--
> Muaz Khan     - www.MuazKhan.com
> MIT License   - www.WebRTC-Experiment.com/licence
> Documentation - github.com/muaz-khan/RecordRTC
> and           - RecordRTC.org
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>RecordRTC: WebRTC audio/video recording ® Muaz Khan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="author" type="text/html" href="https://plus.google.com/+MuazKhan">
    <meta name="author" content="Muaz Khan">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <link rel="stylesheet" href="https://cdn.webrtc-experiment.com/style.css">

    <style>
    audio {
        vertical-align: bottom;
        width: 10em;
    }
    video {
        max-width: 100%;
        vertical-align: top;
    }
    input {
        border: 1px solid #d9d9d9;
        border-radius: 1px;
        font-size: 2em;
        margin: .2em;
        width: 30%;
    }
    p,
    .inner {
        padding: 1em;
    }
    li {
        border-bottom: 1px solid rgb(189, 189, 189);
        border-left: 1px solid rgb(189, 189, 189);
        padding: .5em;
    }
    label {
        display: inline-block;
        width: 8em;
    }
    </style>
    
    <style>
        .recordrtc button {
            font-size: inherit;
        }
        
        .recordrtc button, .recordrtc select {
            vertical-align: middle;
            line-height: 1;
            padding: 2px 5px;
            height: auto;
            font-size: inherit;
            margin: 0;
        }
        
        .recordrtc, .recordrtc .header {
            display: block;
            text-align: center;
            padding-top: 0;
        }
        
        .recordrtc video {
            width: 70%;
        }
        
        .recordrtc option[disabled] {
            display: none;
        }
    </style>
    
    <script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
    <script src="https://cdn.webrtc-experiment.com/gif-recorder.js"></script>
    <script src="https://cdn.webrtc-experiment.com/getScreenId.js"></script>

    <!-- for Edige/FF/Chrome/Opera/etc. getUserMedia support -->
    <script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>
</head>

<body>
    <article>
        <header style="text-align: center;">
            <h1>
                <a href="https://github.com/muaz-khan/RecordRTC">RecordRTC</a>: <a href="https://www.webrtc-experiment.com/" target="_blank">WebRTC</a> audio/video recording ®
                <a href="https://github.com/muaz-khan" target="_blank">Muaz Khan</a>
            </h1>
            <p>
                <a href="https://www.webrtc-experiment.com/">HOME</a>
                <span> &copy; </span>
                <a href="http://www.MuazKhan.com/" target="_blank">Muaz Khan</a> .
                <a href="http://twitter.com/WebRTCWeb" target="_blank" title="Twitter profile for WebRTC Experiments">@WebRTCWeb</a> .
                <a href="https://github.com/muaz-khan?tab=repositories" target="_blank" title="Github Profile">Github</a> .
                <a href="https://github.com/muaz-khan/RecordRTC/issues?state=open" target="_blank">Latest issues</a> .
                <a href="https://github.com/muaz-khan/RecordRTC/commits/master" target="_blank">What's New?</a>
            </p>
        </header>

        <div class="github-stargazers"></div>
        
        <section class="experiment recordrtc">
            <h2 class="header">
                <button id="recording_button">Start Recording</button>
            </h2>
            
            <div style="text-align: center; display: none;">
                <button id="save-to-disk">Save To Disk</button>
                <button id="open-new-tab">Open New Tab</button>
            </div>
            
            <br>

            <video controls muted></video>
        </section>
        
        <script>
            (function() {
                var params = {},
                    r = /([^&=]+)=?([^&]*)/g;

                function d(s) {
                    return decodeURIComponent(s.replace(/\+/g, ' '));
                }

                var match, search = window.location.search;
                while (match = r.exec(search.substring(1))) {
                    params[d(match[1])] = d(match[2]);

                    if(d(match[2]) === 'true' || d(match[2]) === 'false') {
                        params[d(match[1])] = d(match[2]) === 'true' ? true : false;
                    }
                }

                window.params = params;
            })();
        </script>
        
        <script>
            function intallFirefoxScreenCapturingExtension() {
                InstallTrigger.install({
                    'Foo': {
                        // URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                        URL: 'https://addons.mozilla.org/firefox/downloads/file/355418/enable_screen_capturing_in_firefox-1.0.006-fx.xpi?src=cb-dl-hotness',
                        toString: function() {
                            return this.URL;
                        }
                    }
                });
            }

            var recordingDIV = document.querySelector('.recordrtc');
           //var recordingMedia = recordingDIV.querySelector('.recording-media');
            var recordingPlayer = recordingDIV.querySelector('video');
            //var mediaContainerFormat = recordingDIV.querySelector('.media-container-format');
            
            window.onbeforeunload = function() {
                //recordingDIV.querySelector('button').disabled = false;
                //recordingMedia.disabled = false;
                //mediaContainerFormat.disabled = false;
            };
            
            document.getElementById('recording_button').onclick = function() {
                var button = this;

                if(button.innerHTML === 'Stop Recording') {
                    button.disabled = true;
                    button.disableStateWaiting = true;
                    setTimeout(function() {
                        button.disabled = false;
                        button.disableStateWaiting = false;
                    }, 2 * 1000);
                    
                    button.innerHTML = 'Star Recording';

                    function stopStream() {
                        if(button.stream && button.stream.stop) {
                            button.stream.stop();
                            button.stream = null;
                        }
                    }
                    
                    if(button.recordRTC) {
                        if(button.recordRTC.length) {
                            button.recordRTC[0].stopRecording(function(url) {
                                if(!button.recordRTC[1]) {
                                    button.recordingEndedCallback(url);
                                    stopStream();

                                    saveToDiskOrOpenNewTab(button.recordRTC[0]);
                                    return;
                                }

                                button.recordRTC[1].stopRecording(function(url) {
                                    button.recordingEndedCallback(url);
                                    stopStream();
                                });
                            });
                        }
                        else {
                            button.recordRTC.stopRecording(function(url) {
                                button.recordingEndedCallback(url);
                                stopStream();

                                saveToDiskOrOpenNewTab(button.recordRTC);
                            });
                        }
                    }
                    
                    return;
                }
                
                button.disabled = true;
                
                var commonConfig = {
                    onMediaCaptured: function(stream) {
                        button.stream = stream;
                        if(button.mediaCapturedCallback) {
                            button.mediaCapturedCallback();
                        }

                        button.innerHTML = 'Stop Recording';
                        button.disabled = false;
                    },
                    onMediaStopped: function() {
                        button.innerHTML = 'Start Recording';
                        
                        if(!button.disableStateWaiting) {
                            button.disabled = false;
                        }
                    },
                    onMediaCapturingFailed: function(error) {
                        if(error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
                            intallFirefoxScreenCapturingExtension();
                        }
                        
                        commonConfig.onMediaStopped();
                    }
                };

                var mimeType = 'video/mp4';
                
                /*if(recordingMedia.value === 'record-video') {
                    captureVideo(commonConfig);
                    
                    button.mediaCapturedCallback = function() {
                        button.recordRTC = RecordRTC(button.stream, {
                            type: mediaContainerFormat.value === 'Gif' ? 'gif' : 'video',
                            mimeType: isChrome ? null: mimeType,
                            disableLogs: params.disableLogs || false,
                            canvas: {
                                width: params.canvas_width || 320,
                                height: params.canvas_height || 240
                            },
                            frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                        });
                        
                        button.recordingEndedCallback = function(url) {
                            recordingPlayer.src = null;

                            if(mediaContainerFormat.value === 'Gif') {
                                recordingPlayer.pause();
                                recordingPlayer.poster = url;

                                recordingPlayer.onended = function() {
                                    recordingPlayer.pause();
                                    recordingPlayer.poster = URL.createObjectURL(button.recordRTC.blob);
                                };
                                return;
                            }
                            
                            recordingPlayer.src = url;
                            recordingPlayer.play();

                            recordingPlayer.onended = function() {
                                recordingPlayer.pause();
                                recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                            };
                        };
                        
                        button.recordRTC.startRecording();
                    };
                }*/
                
                /*if(recordingMedia.value === 'record-audio') {
                    captureAudio(commonConfig);
                    
                    button.mediaCapturedCallback = function() {
                        var options = {
                            type: 'audio',
                            mimeType: mimeType,
                            bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                            sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                            leftChannel: params.leftChannel || false,
                            disableLogs: params.disableLogs || false,
                            recorderType: webrtcDetectedBrowser === 'edge' ? StereoAudioRecorder : null
                        };

                        if(typeof params.sampleRate == 'undefined') {
                            delete options.sampleRate;
                        }

                        button.recordRTC = RecordRTC(button.stream, options);
                        
                        button.recordingEndedCallback = function(url) {
                            var audio = new Audio();
                            audio.src = url;
                            audio.controls = true;
                            recordingPlayer.parentNode.appendChild(document.createElement('hr'));
                            recordingPlayer.parentNode.appendChild(audio);

                            if(audio.paused) audio.play();

                            audio.onended = function() {
                                audio.pause();
                                audio.src = URL.createObjectURL(button.recordRTC.blob);
                            };
                        };
                        
                        button.recordRTC.startRecording();
                    };
                }*/

                if(recordingMedia.value === 'record-audio-plus-video') {
                    captureAudioPlusVideo(commonConfig);
                    
                    button.mediaCapturedCallback = function() {

                        if(typeof MediaRecorder === 'undefined') { // opera or chrome etc.
                            button.recordRTC = [];

                            if(!params.bufferSize) {
                                // it fixes audio issues whilst recording 720p
                                params.bufferSize = 16384;
                            }

                            var options = {
                                type: 'audio',
                                bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                                sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                                leftChannel: params.leftChannel || false,
                                disableLogs: params.disableLogs || false,
                                recorderType: webrtcDetectedBrowser === 'edge' ? StereoAudioRecorder : null
                            };

                            if(typeof params.sampleRate == 'undefined') {
                                delete options.sampleRate;
                            }

                            var audioRecorder = RecordRTC(button.stream, options);

                            var videoRecorder = RecordRTC(button.stream, {
                                type: 'video',
                                disableLogs: params.disableLogs || false,
                                canvas: {
                                    width: params.canvas_width || 320,
                                    height: params.canvas_height || 240
                                },
                                frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                            });

                            // to sync audio/video playbacks in browser!
                            videoRecorder.initRecorder(function() {
                                audioRecorder.initRecorder(function() {
                                    audioRecorder.startRecording();
                                    videoRecorder.startRecording();
                                });
                            });

                            button.recordRTC.push(audioRecorder, videoRecorder);

                            button.recordingEndedCallback = function() {
                                var audio = new Audio();
                                audio.src = audioRecorder.toURL();
                                audio.controls = true;
                                audio.autoplay = true;

                                audio.onloadedmetadata = function() {
                                    recordingPlayer.src = videoRecorder.toURL();
                                    recordingPlayer.play();
                                };

                                recordingPlayer.parentNode.appendChild(document.createElement('hr'));
                                recordingPlayer.parentNode.appendChild(audio);

                                if(audio.paused) audio.play();
                            };
                            return;
                        }

                        button.recordRTC = RecordRTC(button.stream, {
                            type: 'video',
                            mimeType: mimeType,
                            disableLogs: params.disableLogs || false,
                            // bitsPerSecond: 25 * 8 * 1025 // 25 kbits/s
                        });
                        
                        button.recordingEndedCallback = function(url) {
                            recordingPlayer.muted = false;
                            recordingPlayer.src = url;
                            recordingPlayer.play();

                            recordingPlayer.onended = function() {
                                recordingPlayer.pause();
                                recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                            };
                        };
                        
                        button.recordRTC.startRecording();
                    };
                }
                
                /*if(recordingMedia.value === 'record-screen') {
                    captureScreen(commonConfig);
                    
                    button.mediaCapturedCallback = function() {
                        button.recordRTC = RecordRTC(button.stream, {
                            type: mediaContainerFormat.value === 'Gif' ? 'gif' : 'video',
                            mimeType: mimeType,
                            disableLogs: params.disableLogs || false,
                            canvas: {
                                width: params.canvas_width || 320,
                                height: params.canvas_height || 240
                            }
                        });
                        
                        button.recordingEndedCallback = function(url) {
                            recordingPlayer.src = null;

                            if(mediaContainerFormat.value === 'Gif') {
                                recordingPlayer.pause();
                                recordingPlayer.poster = url;
                                recordingPlayer.onended = function() {
                                    recordingPlayer.pause();
                                    recordingPlayer.poster = URL.createObjectURL(button.recordRTC.blob);
                                };
                                return;
                            }
                            
                            recordingPlayer.src = url;
                            recordingPlayer.play();
                        };
                        
                        button.recordRTC.startRecording();
                    };
                }*/

                /*if(recordingMedia.value === 'record-audio-plus-screen') {
                    captureAudioPlusScreen(commonConfig);
                    
                    button.mediaCapturedCallback = function() {
                        button.recordRTC = RecordRTC(button.stream, {
                            type: 'video',
                            mimeType: mimeType,
                            disableLogs: params.disableLogs || false,
                            // we can't pass bitrates or framerates here
                            // Firefox MediaRecorder API lakes these features
                        });
                        
                        button.recordingEndedCallback = function(url) {
                            recordingPlayer.muted = false;
                            recordingPlayer.src = url;
                            recordingPlayer.play();

                            recordingPlayer.onended = function() {
                                recordingPlayer.pause();
                                recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                            };
                        };
                        
                        button.recordRTC.startRecording();
                    };
                }*/
            };
            
            function captureVideo(config) {
                captureUserMedia({video: true}, function(videoStream) {
                    recordingPlayer.srcObject = videoStream;
                    recordingPlayer.play();
                    
                    config.onMediaCaptured(videoStream);
                    
                    videoStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }
            
            function captureAudio(config) {
                captureUserMedia({audio: true}, function(audioStream) {
                    recordingPlayer.srcObject = audioStream;
                    recordingPlayer.play();
                    
                    config.onMediaCaptured(audioStream);
                    
                    audioStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }

            function captureAudioPlusVideo(config) {
                captureUserMedia({video: true, audio: true}, function(audioVideoStream) {
                    recordingPlayer.srcObject = audioVideoStream;
                    recordingPlayer.play();
                    
                    config.onMediaCaptured(audioVideoStream);
                    
                    audioVideoStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }
            
            function captureScreen(config) {
                getScreenId(function(error, sourceId, screenConstraints) {
                    if (error === 'not-installed') {
                        document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
                    }

                    if (error === 'permission-denied') {
                        alert('Screen capturing permission is denied.');
                    }

                    if (error === 'installed-disabled') {
                        alert('Please enable chrome screen capturing extension.');
                    }
                    
                    if(error) {
                        config.onMediaCapturingFailed(error);
                        return;
                    }

                    captureUserMedia(screenConstraints, function(screenStream) {
                        recordingPlayer.srcObject = screenStream;
                        recordingPlayer.play();
                        
                        config.onMediaCaptured(screenStream);
                        
                        screenStream.onended = function() {
                            config.onMediaStopped();
                        };
                    }, function(error) {
                        config.onMediaCapturingFailed(error);
                    });
                });
            }

            function captureAudioPlusScreen(config) {
                getScreenId(function(error, sourceId, screenConstraints) {
                    if (error === 'not-installed') {
                        document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
                    }

                    if (error === 'permission-denied') {
                        alert('Screen capturing permission is denied.');
                    }

                    if (error === 'installed-disabled') {
                        alert('Please enable chrome screen capturing extension.');
                    }
                    
                    if(error) {
                        config.onMediaCapturingFailed(error);
                        return;
                    }

                    screenConstraints.audio = true;

                    captureUserMedia(screenConstraints, function(screenStream) {
                        recordingPlayer.srcObject = screenStream;
                        recordingPlayer.play();
                        
                        config.onMediaCaptured(screenStream);
                        
                        screenStream.onended = function() {
                            config.onMediaStopped();
                        };
                    }, function(error) {
                        config.onMediaCapturingFailed(error);
                    });
                });
            }
            
            function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
                navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
            }
            
            /*function setMediaContainerFormat(arrayOfOptionsSupported) {
                var options = Array.prototype.slice.call(
                    mediaContainerFormat.querySelectorAll('option')
                );
                
                var selectedItem;
                options.forEach(function(option) {
                    option.disabled = true;
                    
                    if(arrayOfOptionsSupported.indexOf(option.value) !== -1) {
                        option.disabled = false;
                        
                        if(!selectedItem) {
                            option.selected = true;
                            selectedItem = option;
                        }
                    }
                });
            }*/
            
            /*recordingMedia.onchange = function() {
                var options = [];
                if(webrtcDetectedBrowser === 'firefox') {
                    if(this.value === 'record-audio') {
                        options.push('Ogg');
                    }
                    else {
                        options.push('WebM', 'Mp4');
                    }

                    setMediaContainerFormat(options);
                    return;
                }
                if(this.value === 'record-audio') {
                    setMediaContainerFormat(['WAV', 'Ogg']);
                    return;
                }
                setMediaContainerFormat(['WebM', 'Mp4', 'Ogg']);
            };*/

            /*if(webrtcDetectedBrowser === 'edge') {
                // webp isn't supported in Microsoft Edge
                // neither MediaRecorder API
                // so lets disable both video/screen recording options

                console.warn('Neither MediaRecorder API nor webp is supported in Microsoft Edge. You cam merely record audio.');

                recordingMedia.innerHTML = '<option value="record-audio">Audio</option>';
                setMediaContainerFormat(['WAV']);
            }

            if(webrtcDetectedBrowser === 'firefox') {
                // Firefox implemented both MediaRecorder API as well as WebAudio API
                // Their MediaRecorder implementation supports both audio/video recording in single container format
                // Remember, we can't currently pass bit-rates or frame-rates values over MediaRecorder API (their implementation lakes these features)

                recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>' 
                                            + '<option value="record-audio-plus-screen">Audio+Screen</option>' 
                                            + recordingMedia.innerHTML;

                setMediaContainerFormat(['WebM', 'Mp4']);
            }

            if(webrtcDetectedBrowser === 'chrome') {
                recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>' 
                                            + recordingMedia.innerHTML;
                console.info('This RecordRTC demo merely tries to playback recorded audio/video sync inside the browser. It still generates two separate files (WAV/WebM).');
            }*/
            
            function saveToDiskOrOpenNewTab(recordRTC) {
                recordingDIV.querySelector('#save-to-disk').parentNode.style.display = 'block';
                recordingDIV.querySelector('#save-to-disk').onclick = function() {
                    if(!recordRTC) return alert('No recording found.');
                    
                    recordRTC.save();
                };
                
                recordingDIV.querySelector('#open-new-tab').onclick = function() {
                    if(!recordRTC) return alert('No recording found.');
                    
                    window.open(recordRTC.toURL());
                };
            }
        </script>

        <section class="experiment">
            <h2 class="header">
                URL Parameters
            </h2>
            <pre>
// AUDIO
<a href="https://www.webrtc-experiment.com/RecordRTC/?bufferSize=16384&sampleRate=44100">?bufferSize=16384&sampleRate=44100</a>
<a href="https://www.webrtc-experiment.com/RecordRTC/?leftChannel=false&disableLogs=false">?leftChannel=false&disableLogs=false</a>

// VIDEO
<a href="https://www.webrtc-experiment.com/RecordRTC/?canvas_width=1280&canvas_height=720">?canvas_width=1280&canvas_height=720</a>
<a href="https://www.webrtc-experiment.com/RecordRTC/?frameInterval=10">?frameInterval=10</a>
</pre>
        </section>
        
        <section class="experiment">
            <h2 class="header">
                RecordRTC <a href="https://github.com/muaz-khan/RecordRTC">Sources Codes</a> / <a href="https://github.com/muaz-khan/RecordRTC/wiki">Wiki Pages</a>
            </h2>
            <ol>
                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/">RecordRTC Main Demo</a> (Records screen/video/audio in all browsers!)
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/PHP/">RecordRTC-to-PHP</a> 
                    (audio/video recording and uploading to server)
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/Canvas-Recording/">Canvas Recording!</a> (Web-Page Recording)
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/Canvas-Recording/record-canvas-drawings.html">Record Canvas2D Drawings</a> (Huge collection of 2D drawings!)
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/Record-Mp3-or-Wav.html">Record Mp3 or Wav</a> (Pre-recorded audio) i.e. (Audio on Demand)
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/MRecordRTC/">MRecordRTC and writeToDisk/getFromDisk!</a>
                </li>

                <li>
                    <a href="https://www.webrtc-experiment.com/RecordRTC/AudioVideo-on-Firefox.html">Audio+Video Recording on Firefox</a>
                </li>

                <li>
                    <a href="https://github.com/muaz-khan/RecordRTC/tree/master/PHP-and-FFmpeg">
                        RecordRTC / PHP / FFmpeg
                    </a>
                    (Syncing/Merging audio/video in single file!)
                </li>

                <li>
                    <a href="https://github.com/muaz-khan/RecordRTC/tree/master/RecordRTC-to-Nodejs">RecordRTC-to-Nodejs</a> 
                    (used ffmpeg to merge wav/webm in single WebM container)
                </li>

                <li>
                    <a href="https://github.com/muaz-khan/RecordRTC/tree/master/RecordRTC-to-ASPNETMVC">RecordRTC-to-ASP.NET MVC</a> 
                    (audio/video recording and uploading to server)
                </li>
                
                <li>
                    <a href="https://github.com/muaz-khan/RecordRTC/tree/master/RecordRTC-over-Socketio">RecordRTC-to-Socket.io</a> 
                    (used ffmpeg to merge wav/webm in single WebM container)
                </li>

                <li><a href="https://www.webrtc-experiment.com/ffmpeg/">RecordRTC and ffmpeg-asm.js</a> (ffmpeg inside the browser!)</li>
            </ol>
        </section>

        <script>
                // todo: need to check exact chrome browser because opera also uses chromium framework
                var isChrome = !!navigator.webkitGetUserMedia;
                
                // DetectRTC.js - https://github.com/muaz-khan/WebRTC-Experiment/tree/master/DetectRTC
                // Below code is taken from RTCMultiConnection-v1.8.js (http://www.rtcmulticonnection.org/changes-log/#v1.8)
                var DetectRTC = {};

                (function () {
                    
                    var screenCallback;
                    
                    DetectRTC.screen = {
                        chromeMediaSource: 'screen',
                        getSourceId: function(callback) {
                            if(!callback) throw '"callback" parameter is mandatory.';
                            screenCallback = callback;
                            window.postMessage('get-sourceId', '*');
                        },
                        isChromeExtensionAvailable: function(callback) {
                            if(!callback) return;
                            
                            if(DetectRTC.screen.chromeMediaSource == 'desktop') return callback(true);
                            
                            // ask extension if it is available
                            window.postMessage('are-you-there', '*');
                            
                            setTimeout(function() {
                                if(DetectRTC.screen.chromeMediaSource == 'screen') {
                                    callback(false);
                                }
                                else callback(true);
                            }, 2000);
                        },
                        onMessageCallback: function(data) {
                            if (!(typeof data == 'string' || !!data.sourceId)) return;
                            
                            console.log('chrome message', data);
                            
                            // "cancel" button is clicked
                            if(data == 'PermissionDeniedError') {
                                DetectRTC.screen.chromeMediaSource = 'PermissionDeniedError';
                                if(screenCallback) return screenCallback('PermissionDeniedError');
                                else throw new Error('PermissionDeniedError');
                            }
                            
                            // extension notified his presence
                            if(data == 'rtcmulticonnection-extension-loaded') {
                                if(document.getElementById('install-button')) {
                                    document.getElementById('install-button').parentNode.innerHTML = '<strong>Great!</strong> <a href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk" target="_blank">Google chrome extension</a> is installed.';
                                }
                                DetectRTC.screen.chromeMediaSource = 'desktop';
                            }
                            
                            // extension shared temp sourceId
                            if(data.sourceId) {
                                DetectRTC.screen.sourceId = data.sourceId;
                                if(screenCallback) screenCallback( DetectRTC.screen.sourceId );
                            }
                        },
                        getChromeExtensionStatus: function (callback) {
                            if (!!navigator.mozGetUserMedia) return callback('not-chrome');
                            
                            var extensionid = 'ajhifddimkapgcifgcodmmfdlknahffk';

                            var image = document.createElement('img');
                            image.src = 'chrome-extension://' + extensionid + '/icon.png';
                            image.onload = function () {
                                DetectRTC.screen.chromeMediaSource = 'screen';
                                window.postMessage('are-you-there', '*');
                                setTimeout(function () {
                                    if (!DetectRTC.screen.notInstalled) {
                                        callback('installed-enabled');
                                    }
                                }, 2000);
                            };
                            image.onerror = function () {
                                DetectRTC.screen.notInstalled = true;
                                callback('not-installed');
                            };
                        }
                    };
                    
                    // check if desktop-capture extension installed.
                    if(window.postMessage && isChrome) {
                        DetectRTC.screen.isChromeExtensionAvailable();
                    }
                })();
                
                DetectRTC.screen.getChromeExtensionStatus(function(status) {
                    if(status == 'installed-enabled') {
                        if(document.getElementById('install-button')) {
                            document.getElementById('install-button').parentNode.innerHTML = '<strong>Great!</strong> <a href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk" target="_blank">Google chrome extension</a> is installed.';
                        }
                        DetectRTC.screen.chromeMediaSource = 'desktop';
                    }
                });
                
                window.addEventListener('message', function (event) {
                    if (event.origin != window.location.origin) {
                        return;
                    }
                    
                    DetectRTC.screen.onMessageCallback(event.data);
                });
            </script>
            
            <section class="experiment">
                <h2>Screen Capturing Prerequisites</h2>
                <ol>
                    <li>
                        Chrome? 
                        <a href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk" target="_blank">Store</a>
                        / <a href="https://github.com/muaz-khan/Chrome-Extensions/tree/master/desktopCapture">Source Code</a>
                        / 
                        <button onclick="!!navigator.webkitGetUserMedia && !!window.chrome && !!chrome.webstore && !!chrome.webstore.install && chrome.webstore.install('https://chrome.google.com/webstore/detail/ajhifddimkapgcifgcodmmfdlknahffk', function() {location.reload();})" id="install-button" style="font-size:inherit; padding-bottom:0;">Click to Install</button>
                    </li>
                    
                    <li>
                        Firefox? <a href="https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/">Store</a> / <a href="https://github.com/muaz-khan/Firefox-Extensions/tree/master/enable-screen-capturing">Source Code</a> / <button onclick="intallFirefoxScreenCapturingExtension(); this.disabled = true;" style="font-size:inherit; padding-bottom:0;">Click to Install</button>
                    </li>
                </ol>
            </section>

        <section class="experiment own-widgets">
            <h2 class="header" id="updates" style="color: red; padding-bottom: .1em;"><a href="https://github.com/muaz-khan/RecordRTC/issues" target="_blank">RecordRTC Issues</a>
            </h2>
            <div id="github-issues"></div>
        </section>

        <section class="experiment">
            <h2 class="header" id="feedback">Feedback</h2>
            <div>
                <textarea id="message" style="border: 1px solid rgb(189, 189, 189); height: 8em; margin: .2em; outline: none; resize: vertical; width: 98%;" placeholder="Have any message? Suggestions or something went wrong?"></textarea>
            </div>
            <button id="send-message" style="font-size: 1em;">Send Message</button>
            <small style="margin-left: 1em;">Enter your email too; if you want "direct" reply!</small>
        </section>

        <section class="experiment">
            <h2 class="header">Using RecordRTC...</h2>

            <ol>
                <li>
                    You can record both Audio/Video in single file in Firefox.
                </li>

                <li>
                    You can record both Audio/Screen in single file in Firefox.
                </li>

                <li>
                    You can record audio as WAV and video as WebM in Chrome/Opera.
                </li>

                <li>
                    You can record audio as WAV in Microsoft Edge.
                </li>

                <li>
                    You can record remote audios (RTCPeerConnection.onaddstream) in Firefox using "recorderType:StereoAudioRecorder"
                </li>

                <li>
                    You can record Gif in all browsers.
                </li>
            </ol>

            <p style="margin-top: 0;">
                You can even control buffer-size, sample-rates, video-resolutions, etc.
            </p>
        </section>

        <section class="experiment">
            <h2 class="header">Technical Guide</h2>
            <ol>
                <li>
                    Chrome allows getUserMedia invocation on majority of non-file protocols e.g. HTTP/HTTPS/ or inside chrome extension pages. Though, there is always options to use CL (command-line) flags to support file protocols.
                </li>

                <li>
                    (
                    <strong>In Chrome</strong>) RecordRTC captures video frames via Canvas2D; which is encoded in webp-DataURL; now it is using a library named “weppy” which encodes webp into webm.
                </li>

                <li>
                    (
                    <strong>In Firefox</strong>) RecordRTC is using MediaRecorder API; which supports both audio/video recordings, both in single and multiple files.
                </li>

                <li>
                    (
                    <strong>In Chrome</strong>) RecordRTC is using WebAudio API for audio-recording. Such API has many issues e.g. unable to capture mono audio in wav format; <a href="https://www.webrtc-experiment.com/demos/remote-stream-recording.html">unable to capture remote audio</a>; failure on XP SP2; etc.
                </li>

                <li>
                    (
                    <strong>In Chrome</strong>) If you’re using notebook’s built-in audio input device for audio recording; then you may get "blank" blob.
                </li>

                <li>
                    (
                    <strong>In Chrome</strong>) RecordRTC is incapable to record audio/video in a single file; however there is ffmpeg merging solution available on Github repository.
                </li>
            </ol>
        </section>

        <section class="experiment">
            <h2 class="header">
                How to use <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>?</h2>
            <pre>
&lt;script src="//cdn.webrtc-experiment.com/RecordRTC.js"&gt;&lt;/script&gt;
</pre>
        </section>
        
        <section class="experiment">
            <h2 class="header">
                How to record audio using <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>?</h2>
            <pre>
var recordRTC = <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>(mediaStream, {
    <strong>recorderType</strong>: StereoAudioRecorder // optionally force WebAudio API to record audio
}); 
recordRTC.<strong>startRecording</strong>(); 
recordRTC.<strong>stopRecording</strong>(function(<strong>audioURL</strong>) { 
    mediaElement.src = audioURL; 
});
</pre>
        </section>


        <section class="experiment">
            <h2 class="header">
                How to record video using <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>?</h2>
            <pre>
var options = {
    <strong>type</strong>: 'video',
    <strong>video</strong>: { width: 320, height: 240 },
    <strong>canvas</strong>: { width: 320, height: 240 },
    <strong>recorderType</strong>: WhammyRecorder // optional
}; 
    
var recordRTC = <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>(mediaStream, options); 
recordRTC.<strong>startRecording</strong>(); 
recordRTC.<strong>stopRecording</strong>(function(<strong>videoURL</strong>) { 
    mediaElement.src = videoURL; 
});
</pre>

        </section>

        <section class="experiment">

            <h2 class="header">
                How to record animated GIF using <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>?</h2>
            <pre>
// you must link: // https://www.webrtc-experiment.com/gif-recorder.js
var options = {
    <strong>type</strong>: 'gif',
    <strong>video</strong>: { width: 320, height: 240 },
    <strong>canvas</strong>: { width: 320, height: 240 },
    <strong>frameRate</strong>: 200,
    <strong>quality</strong>: 10
}; 
        
var recordRTC = <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">RecordRTC</a>(mediaStream, options); 
recordRTC.<strong>startRecording</strong>(); recordRTC.
<strong>stopRecording</strong>(function(<strong>gifURL</strong>) {
    mediaElement.src = gifURL;
});
</pre>
        </section>

        <section class="experiment">
            <h2>
                Possible <a href="https://github.com/muaz-khan/RecordRTC/issues" target="_blank">

                                 issues</a>/<a href="https://github.com/muaz-khan/RecordRTC/issues" target="_blank">failures</a>:
            </h2>
            <p>
                (
                <strong>In Chrome</strong>) The biggest issue is that RecordRTC is
                <strong>unable to record</strong> both audio and video streams in single file.
                <br />
                <br /> Do you know "RecordRTC" fails recording audio because following conditions fails (
                <strong>applies only to chrome</strong>):
                <ol>
                    <li>Sample rate and channel configuration must be the same for input and output sides on Windows i.e. audio input/output devices must match</li>
                    <li>Only the Default microphone device can be used for capturing.</li>
                    <li>The requesting scheme is must be one of the following: http, https, chrome, extension's, or file (only works with --allow-file-access-from-files)</li>
                    <li>The browser must be able to create/initialize the metadata database for the API under the profile directory</li>
                </ol>
            </p>
        </section>
        <section class="experiment">
            <p style="margin-top: 0;">
                RecordRTC is MIT licensed on Github! <a href="https://github.com/muaz-khan/RecordRTC" target="_blank">Documentation</a>
            </p>
        </section>

        <section class="experiment own-widgets latest-commits">
            <h2 class="header" id="updates" style="color: red; padding-bottom: .1em;"><a href="https://github.com/muaz-khan/RecordRTC/commits/master" target="_blank">Latest Updates</a>
            </h2>
            <div id="github-commits"></div>
        </section>
    </article>

    <a href="https://github.com/muaz-khan/RecordRTC" class="fork-left"></a>

    <footer>
        <p>
            <a href="https://www.webrtc-experiment.com/">WebRTC Experiments</a> © <a href="https://plus.google.com/+MuazKhan" rel="author" target="_blank">Muaz Khan</a>
            <a href="mailto:muazkh@gmail.com" target="_blank">muazkh@gmail.com</a>
        </p>
    </footer>

    <!-- commits.js is useless for you! -->
    <script>
        window.useThisGithubPath = 'muaz-khan/RecordRTC';
    </script>
    <script src="https://cdn.webrtc-experiment.com/commits.js" async></script>
</body>

</html>
