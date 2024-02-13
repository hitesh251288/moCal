<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Cube Game</title>
        <style>
            .box{
                width: 50px;
                height: 50px;
                background-color: red;
                cursor: pointer;
                display:inline-block;
                margin: 2px;
            }
            .green {background-color: green;}
            .yellow {background-color: yellow;}
        </style>
    </head>
    <body>
        <div>
            <form id="startForm">
            Username : <input type="text" id="username" />
            Cube Size : <input type="number" id="cubeSize" />
            <button type="button" onclick="startGame()">Start</button>
            </form>
        </div>
    </body>
    <div id="cube"></div>
    <div> Timer : <span id="timer">0</span> seconds</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js" integrity="sha512-NQfB/bDaB8kaSXF8E77JjhHG5PM6XVRxvHzkZiwl3ddWCEPBa23T76MuWSwAJdMGJnmQqM0VeY9kFszsrBEFrQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let intervalId,startTime;
                
                window.startGame=function(){ 
                    const username = document.getElementById('username').value;
                    const cubeSize = parseInt(document.getElementById('cubeSize').value,10);
                    const cube = document.getElementById('cube');
                    cube.innerHTML = '';
                    clearInterval(intervalId);
                    document.getElementById('timer').textContent = '0';
                    
                    for(let i=0;i<cubeSize;i++){
                        const box = document.createElement('div');
                        box.classList.add('box',i===0 ? 'yellow' : 'red');
                        box.addEventListener('click',function(){
                            boxClicked(this.username,cubSize);
                        });
                        cube.appendChild(box);
                    }
                    
                    startTime = Date.now();
                    intervalId = setInterval(function(){
                       const elapsed = Math.round(Date.now() - startTime) / 1000;
                       document.getElementById('timer').textContent = elapsed.toString();
                    },1000);
                });
                
                function boxClicked(box,username,cubeSize){
                    if(!box.classList.contains('yellow')) return;
                    box.classList.remove('yellow');
                    box.classList.add('green');
                    const remainingBoxes = document.querySelectorAll('.box:not(.green)');
                    if(remainingBoxes.length === 0){
                        clearInterval(intervalId);
                        const timetaken = parseInt(document.getElementById('timer').textContent,10);
                        submitResults(username,cubeSize,timeTaken);
                        return;
                    }
                    const nextYellowIndex = Math.floor(Math.random() * remainingBoxes.length);
                    remainingBoxes[nextYellowIndex].classList.add('yellow');
                }
                
                function submitResults(username, cubeSize, timeTaken) {
                fetch('/game/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ username, cubeSize, time: timeTaken }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    alert('Game over! Your time: ' + timeTaken + ' seconds');
                })
                .catch(error => console.error('Error:', error));
            }
                
            });
        </script>
    
