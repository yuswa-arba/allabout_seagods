<?php // if (isset($_POST['measurement']) && $_POST['measurement']): ?>

    <html>
        <style>

            body{
                width: 1180px;

            }
            .container{
                margin: 0 auto;
                width:100%;
                display:block;
            }

            .container .left-container{
                display:inline-block;
                width:50%;
            }

            .container .right-container{
                float:right;
                display:inline-block;
                width:50%;
            }
            .left-container h1{
                font-weight: bold;
                text-align: center;
                margin-bottom: 0;
                padding-bottom: 0;
            }

            .left-container h2{
                margin-top: 0px;
                text-align: center;
                font-size: 22px;
            }

            .image-wrapper img{
                margin: 0 auto;
                display:block;
                height: auto;
                width: 300px;
            }

            .right-container .form-group{
                padding: 3px 0px;
            }

            .right-container .form-group label.measure-header{
            }

            .right-container .form-group label.measure{
                float:right;
            }
            .right-container .form-group label.measure-value{
                float:right;
            }


        </style>

        <div class="container">
            <div class="left-container">
                <h1>Taking your Measurements</h1>
                <h2>Customer Details</h2>

                <div class="form-wrapper">

                </div>
                <div class="image-wrapper">
                    <img src="../custom/public/images/sakarioka/CMD.jpg"/>
                </div>
            </div>
            <div class="right-container">
                <div class="form-wrapper">
                    <div class="form-group">
                        <label class="measure-header">1. Total body weight</label>
                        <label id="measureTotalBodyHeight" class="measure"><?php echo isset($_POST['measureTotalBodyHeight']) ? $_POST['measureTotalBodyHeight'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">2. Head</label>
                        <label class="measure-value"></label>
                        <label  id="measureHead" class="measure"><?php echo isset($_POST['measureHead']) ? $_POST['measureHead'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">3. Neck</label>
                        <label class="measure-value"></label>
                        <label id="measureNeck" class="measure"><?php echo isset($_POST['measureNeck']) ? $_POST['measureNeck'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">4. Bust / Chest</label>
                        <label class="measure-value"></label>
                        <label  id="measureBustChest" class="measure"><?php echo isset($_POST['measureBustChest']) ? $_POST['measureBustChest'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">5. Waist</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureWaist']) ? $_POST['measureWaist'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">6. Stomach</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureStomach']) ? $_POST['measureStomach'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">7. Abdomen</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureAbdomen']) ? $_POST['measureAbdomen'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">8. Hip</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureHip']) ? $_POST['measureHip'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">9. Shoulder</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulder']) ? $_POST['measureShoulder'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">10. Shoulder to Elbow</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulderToElbow']) ? $_POST['measureShoulderToElbow'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">11. Shoulder to Wrist</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulderToWrist']) ? $_POST['measureShoulderToWrist'] :'';?> cm</label>
                    </div>


                    <div class="form-group">
                        <label class="measure-header">12. Arm Hole</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureArmHole']) ? $_POST['measureArmHole'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">13. Upper Arm</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureUpperArm']) ? $_POST['measureUpperArm'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">14. Bicep</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureBicep']) ? $_POST['measureBicep'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">15. Elbow</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureElbow']) ? $_POST['measureElbow'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">16. Forarm</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureForarm']) ? $_POST['measureForarm'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">17. Wrist</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureWrist']) ? $_POST['measureWrist'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">18. Outside Leg Length</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureOutsideLegLength']) ? $_POST['measureOutsideLegLength'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">19. Inside Leg Length</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureInsideLegLength']) ? $_POST['measureOutsideLegLength'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">20. Upper Thigh</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureUpperThigh']) ? $_POST['measureUpperThigh'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">21. Thigh</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureThigh']) ? $_POST['measureThigh']:'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">22. Above Knee</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureAboveKnee']) ? $_POST['measureAboveKnee'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">23. Knee</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureKnee']) ? $_POST['measureKnee']:'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">24. Below Knee</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureBelowKnee']) ? $_POST['measureBelowKnee']:'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">25. Calf</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureCalf']) ? $_POST['measureCalf'] :'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">26. Below Calf</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureBelowCalf']) ? $_POST['measureBelowCalf']:'';?> cm</label>
                    </div>

                    <div class="form-group">
                        <label class="measure-header">27. Above Ankle</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureAboveAnkle']) ? $_POST['measureAboveAnkle'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">28. Shoulder to Bust</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulderToBust']) ? $_POST['measureShoulderToBust'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">29. Shoulder to Waist</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulderToWaist']) ? $_POST['measureShoulderToWaist'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">30. Shoulder to Hip</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureShoulderToHip']) ? $_POST['measureShoulderToHip'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">31. Hip to Knee Length</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureHipToKneeLength']) ? $_POST['measureHipToKneeLength'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">32. Knee to Ankle Length</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureKneeToAnkleLength']) ? $_POST['measureKneeToAnkleLength'] :'';?> cm</label>
                    </div>
                    <div class="form-group">
                        <label class="measure-header">33. Back Shoulder</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureBackShoulder']) ? $_POST['measureBackShoulder'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">34. Dorsum</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureDorsum']) ? $_POST['measureDorsum'] :'';?> cm</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="measure-header">35. Crotch Point</label>
                        <label class="measure-value"></label>
                        <label class="measure"><?php echo isset($_POST['measureCrotchPoint']) ? $_POST['measureCrotchPoint'] :'';?> cm</label>
                    </div>
                </div>
            </div>
        </div>

    </html>

<?php // endif; ?>