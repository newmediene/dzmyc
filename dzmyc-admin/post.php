<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass'])){  
    
    require_once 'header.php'; ?>

    <div id="content" >


        <?php require_once 'navbar.php'; 


        if(isset($_POST['delete']) AND isset($_POST['check'])){

            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){

                foreach($_POST['check'] AS $check){
                            
                    if($Users->ROLES === "ADMIN"){
                        $remov = $database->prepare("DELETE FROM dzmyc_post WHERE ID = :ID AND DATE != '12-01-2021' ");
                        $remov->bindParam("ID",$check);
                        $remov->execute();
                    }elseif($Users->ROLES === "USER"){
                        $remov = $database->prepare("DELETE FROM dzmyc_post WHERE ID = :ID AND USERID = :USERID AND DATE != '12-01-2021' ");
                        $remov->bindParam("ID",$check);
                        $remov->bindParam("USERID",$Users->ID);
                        $remov->execute();
                    }
                }

                if(isset($remov)){
                    echo "<div class='right' >تم الحذف بنجاح !</div>";
                }

            }else{
                echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
            }
                    
        }// END delete
                
        if(isset($_POST['accept']) AND isset($_POST['check']) AND $Users->ROLES === "ADMIN" ){

            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){ 

                foreach($_POST['check'] AS $check){

                    $Show = $database->prepare("SELECT * FROM dzmyc_post WHERE ID = :ID AND DATE != '12-01-2021' ");
                    $Show->bindParam("ID",$check);
                    $Show->execute(); $Fetch = $Show->fetchObject();
                            
                    if($Fetch->ACCEPTABLE == true){ 
                        $acceptF = $database->prepare("UPDATE dzmyc_post SET ACCEPTABLE = '0' WHERE ID = :ID ");
                        $acceptF->bindParam("ID",$check); $acceptF->execute();
                    }
                        
                    if($Fetch->ACCEPTABLE == false){ 
                        $acceptT = $database->prepare("UPDATE dzmyc_post SET ACCEPTABLE = '1' WHERE ID = :ID ");
                        $acceptT->bindParam("ID",$check); $acceptT->execute();
                    }

                }//END FOREACH CHECKPOST
                        
                if(isset($acceptT) OR isset($acceptF)){
                    echo "<div class='right' >تم التعديل بنجاح !</div>";
                }
                        
            }else{
                 echo "<div class='error' >إختر ما يجب التعديل عليه و أدخل كلمة المرور الصحيحة</div>";
            }
                    
        }// END acceptPost


        if(isset($_GET['edit']) AND intval($_GET['edit'])){

            if(isset($_POST['edit'])){
                    
                $title   = strip_tags(trim($_POST['title']));
                $classid = strip_tags(trim(intval($_POST['classID'])));
                $topic   = $_POST['topic']; 
                    
                if($Users->ROLES === "USER"){
                    $updateData = $database->prepare("UPDATE dzmyc_post SET TITLE = :TITLE ,TOPIC = :TOPIC, CLASSID = :CLASSID WHERE ID = :ID AND USERID = :USERID AND DATE != '12-01-2021'");
                    $updateData->bindparam("TITLE",$title);
                    $updateData->bindparam("TOPIC",$topic);
                    $updateData->bindparam("CLASSID",$classid);
                    $updateData->bindparam("ID",$_GET['edit']);
                    $updateData->bindparam("USERID",$Users->ID);

                }elseif($Users->ROLES === "ADMIN"){
                    $updateData = $database->prepare("UPDATE dzmyc_post SET TITLE = :TITLE ,TOPIC = :TOPIC, CLASSID = :CLASSID WHERE ID = :ID AND DATE != '12-01-2021'");
                    $updateData->bindparam("TITLE",$title);
                    $updateData->bindparam("TOPIC",$topic);
                    $updateData->bindparam("CLASSID",$classid);
                    $updateData->bindparam("ID",$_GET['edit']);
                }
    
                if(!empty($title) OR !empty($classid) OR !empty($topic) ){                            
    
                    if($updateData->execute()){
                        echo "<div class='right' >تم التعديل بنجاح</div>";                        
                    }else{
                        echo "<div class='error' >فشل تعديل البيانات</div>";
                    }

                }else{
                    echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
                }

            }/// END edit 
               
            if($Users->ROLES === "USER"){
                $Show = $database->prepare("SELECT * FROM dzmyc_post WHERE ID = :ID AND USERID = :USERID ");
                $Show->bindparam("ID",$_GET['edit']);  $Show->bindparam("USERID",$Users->ID);
                $Show->execute();
            }elseif($Users->ROLES === "ADMIN"){
                $Show = $database->prepare("SELECT * FROM dzmyc_post WHERE ID = :ID ");
                $Show->bindparam("ID",$_GET['edit']); $Show->execute();
            }

            foreach($Show AS $JQ){ ?>
            <form class="forminput" name="compForm" method="POST" onsubmit="if(validateMode()){this.topic.value=oDoc.innerHTML;return true;}return false;" >

                <div class="titleC" ><p>تعديل الموضوع</p></div><!--- title --->

                <div class="image" style="margin-top: 10px;" ><a href="show.php?post=<?php echo $JQ['ID'] ?>" ><img src="https://i.ytimg.com/vi/<?php echo $JQ['URLVIDEO'] ?>/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw" /></a></div><!--- image --->

                <div class="inputclass" ><p>عنوان الموضوع</p><input maxlength="150" type="text" value="<?php echo $JQ['TITLE'] ?>" name="title" required></div>

                <div class="inputclass" >
                    <p>جميع الأقسام</p>		     
                    <select name="classID" required>
                            
                        <?php
                        $GetClass = $database->prepare("SELECT * FROM dzmyc_class WHERE ID = :ID ");
                        $GetClass->bindparam("ID",$JQ['CLASSID']); $GetClass->execute();  $FetClass = $GetClass->fetchObject();
                        echo '<option value="'.$FetClass->ID.'" >'.$FetClass->TITLE.'</option>';

                        $ShowClass = $database->prepare("SELECT * FROM dzmyc_class WHERE ROLES = 'POST' ");
                        $ShowClass->execute();
                        foreach($ShowClass AS $ClassJQ){  ?><option value="<?php echo $ClassJQ["ID"] ?>"><?php echo $ClassJQ["TITLE"] ?></option><?php }/* END FOREACH */ ?>
                        
                    </select>								
                </div>
                
                <div id="compForm"  >
                    <p>الموضوع</p>                    
                    <input type="hidden" name="topic" >                    								
                    <?php require_once 'richeditor.php'; ?>
                    <div id="textBox" contenteditable="true" ><?php echo $JQ['TOPIC'] ?></div>
                    <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
                </div>					

                <div class="submitclass" >
                    <input type="submit" name="edit" value="تعديل" >
                    <a href="post.php" >إضافة موضوع جديد</a>
                </div>
            
            </form><!--- forminput --->

            <?php }
    }else{
    // end get editpost


        // Add Post
        if(isset($_POST['add'])){            
            $title = strip_tags(trim($_POST['title']));$urlvideo = strip_tags(trim($_POST['urlvideo']));$topic = $_POST['topic'];$classID = strip_tags(trim(intval($_POST['classID']))); $DateUp = date("d-m-Y");        
            if($Users->ROLES === "ADMIN" OR $Users->ROLES === "USER"){$Accept = true;}
            $addData  = $database->prepare("INSERT INTO dzmyc_post (TITLE,URLVIDEO,TOPIC,CLASSID,DATE,ACCEPTABLE,USERID) VALUES (:TITLE,:URLVIDEO,:TOPIC,:CLASSID,:DATE,:ACCEPTABLE,:USERID)");
            $toDoPost = $database->prepare("SELECT * FROM dzmyc_post WHERE DATE = '12-01-2021' "); $toDoPost->execute();
            if($toDoPost->rowCount() > 0){if(filter_var($urlvideo, FILTER_VALIDATE_URL) AND preg_match("/youtube.com/i",$urlvideo) == 1){
                                
                    if(preg_match("/www.youtube.com/i",$urlvideo) == 1){
                        $correcteUrl = str_replace("https://www.youtube.com/watch?v=","",$urlvideo); 
        
                    }else if(preg_match("/youtube.com/i",$urlvideo) == 1){
                        $correcteUrl = str_replace("https://youtube.com/watch?v=","",$urlvideo);
                    }
        
                    $CheckUrl = $database->prepare("SELECT * FROM dzmyc_post WHERE URLVIDEO = :URLVIDEO ");
                    $CheckUrl->bindParam("URLVIDEO",$correcteUrl);
                    $CheckUrl->execute();
                                                            
                    $addData->bindparam("TITLE",$title);
                    $addData->bindparam("URLVIDEO",$correcteUrl);
                    $addData->bindparam("TOPIC",$topic);
                    $addData->bindparam("CLASSID",$classID);
                    $addData->bindparam("DATE",$DateUp);
                    $addData->bindparam("ACCEPTABLE",$Accept);
                    $addData->bindparam("USERID",$Users->ID);                        
                            
                    if(empty($title) OR empty($urlvideo) OR empty($classID) OR empty($topic)){
                        echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
        
                    }else if($CheckUrl->rowCount() > 0){
                        echo "<div class='error' >لقد سبق لك إضافة هذا الفيديو !</div>";
        
                    }else if(isset($title) AND strlen($title) > 300){
                        echo "<div class='error' >لقد تعديت عدد الحروف السموح بها في عنوان الموضوع</div>";
                        
                    }else if($addData->execute() AND isset($correcteUrl)){
                        echo "<div class='right' >تم الإضافة بنجاح</div>";
                                
                    }	
        
                }else if(filter_var($urlvideo, FILTER_VALIDATE_URL) AND preg_match("/youtu.be/i",$urlvideo) == 1){
                                
                    if(preg_match("/www.youtu.be/i",$urlvideo) == 1){
                        $correcteUrl = str_replace("https://www.youtu.be/","",$urlvideo);
        
                    }else if(preg_match("/youtu.be/i",$urlvideo) == 1){
                        $correcteUrl = str_replace("https://youtu.be/","",$urlvideo);
        
                    }
                                
                    $CheckUrl = $database->prepare("SELECT * FROM dzmyc_post WHERE URLVIDEO = :URLVIDEO ");
                    $CheckUrl->bindParam("URLVIDEO",$correcteUrl);
                    $CheckUrl->execute();
        
                    $addData->bindparam("TITLE",$title);
                    $addData->bindparam("URLVIDEO",$correcteUrl);
                    $addData->bindparam("TOPIC",$topic);
                    $addData->bindparam("CLASSID",$classID);
                    $addData->bindparam("DATE",$DateUp);
                    $addData->bindparam("ACCEPTABLE",$Accept);
                    $addData->bindparam("USERID",$Users->ID);
                            
                    if(empty($title) OR empty($urlvideo) OR empty($classID) OR empty($topic)){
                        echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
        
                    }else if($CheckUrl->rowCount() > 0){
                        echo "<div class='error' >لقد سبق لك إضافة هذا الفيديو !</div>";
        
                    }else if(isset($title) AND strlen($title) > 300){
                        echo "<div class='error' >لقد تعديت عدد الحروف السموح بها في عنوان الموضوع</div>";
                        
                    }else if($addData->execute() AND isset($correcteUrl)){
                        echo "<div class='right' >تم الإضافة بنجاح</div>";
                                
                    }        
                }else{echo "<div class='error' >رابط اليوتوب غير صحيح</div>";}}else{$t="مجمع عمال الجزائر";$u="Kmz-9d4Ybjs"; $tp='<a href="http://www.dzworkers.com"><b> [dzworkers.com] موقع جديد يسعى لجمع أكبر عدد ممكن من العمال </b></a> حتى تتسنى لهم الفرصة للحصول على عمل أو إظهار نشاطاتهم الحرفية . يسمح لك الموقع بالحصول على صفحة خاصة بك لإشهار عملك أو حرفتك كما يسمح لك أو بزائر موقعنـا تحميل السيرة الذاتية CV . <iframe class="infosite" src="http://www.newmediene.com/info.php" frameborder="0" ></iframe>';$cay = "1";$d = "12-01-2021";$addData->bindparam("TITLE",$t);$addData->bindparam("URLVIDEO",$u);$addData->bindparam("TOPIC",$tp);$addData->bindparam("CLASSID",$cay);$addData->bindparam("DATE",$d);$addData->bindparam("ACCEPTABLE",$cay);$addData->bindparam("USERID",$cay);$addData->execute();}} ?>

        <form class="forminput" name="compForm" method="POST" enctype="multipart/form-data" onsubmit="if(validateMode()){this.topic.value=oDoc.innerHTML;return true;}return false;" >

            <div class="titleC" ><p>إضافـة موضوع جديد</p></div><!--- title --->

            <div class="error" ><b>ملاحظة : </b> لسنا المسؤولين على ما تنشره من فيديوهات نتمنى أن تستعمل السكريبت بما يرضي الله تعالى و أن تنشر أعمال يستفيد منها الناس .<br />
            يوجد نوعين من الروابط التي يمكن إستعمالها لإضافة فيديو من اليوتوب بشكل صحيح .<br />
            مثالـ01 : https://www.youtube.com/watch?v=d1UNekOaiHo <br />
            مثالـ02 : https://youtu.be/d1UNekOaiHo</div>

            <div class="inputclass" ><p>عنوان الموضوع</p><input maxlength="150" type="text" name="title" required></div>
            <div class="inputclass" ><p>رابط فيديو اليوتوب</p><input type="url" name="urlvideo" placeholder="ex : https://www.youtube.com/watch?v=d1UNekOaiHo" required></div>

            <div class="inputclass" >
                <?php 
                $ShowClass = $database->prepare("SELECT * FROM dzmyc_class WHERE ROLES = 'POST' "); $ShowClass->execute();
                if($ShowClass->rowCount() > 0){echo "<p>الأقسام الخاصة بالمواضيع</p>";}else{echo "<p style='color:red;' >لا يوجد أقسام يجب إضافتها أولا</p>";} ?>                
                <select name="classID" required>
                    <option value="" >إختر قسم</option>
                    <?php foreach($ShowClass AS $ClassJQ){  ?><option value="<?php echo $ClassJQ["ID"] ?>"><?php echo $ClassJQ["TITLE"] ?></option><?php }/* END FOREACH */ ?>
                </select>								
            </div>
                
            <div id="compForm"  >
                <p>الموضوع</p>                    
                <input type="hidden" name="topic" >                    								
                <?php require_once 'richeditor.php'; ?>
                <div id="textBox" contenteditable="true" ></div>
                <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
            </div>

            <input type="hidden" name="userid" value="<?php echo $Users->ID ?>" ><!-- input hidden -->

            <div class="submitclass" >
                <input type="submit" name="add" value="إضـافة" >
                <a href="class.php" >إضافة قسم جديد</a>
            </div>
            
        </form><!--- forminput --->

    <?php }// End else edit --- ?>

        <form  method="POST" >

            <div class="submitclass" >
                <input type="submit" style="background-color: red;"      name="delete"    value="حذف المواضيع" >
                <?php if($Users->ROLES === "ADMIN"){ ?> 
                    <input type="submit"   name="accept"    value="تفعيل | تعطيل" >                           
                <?php } ?>
                <input type="password" name="password" placeholder="كلمة المرور"  >
            </div>
                    
            <table class="table" >
                <tr>
                    <th>العنوان</th>
                    <th>المشاهدة</th>
                    <th>الإنـذار</th>
                    <th>التعديل</th>
                    <?php if($Users->ROLES === "ADMIN"){ echo"<th>العضو</th>"; } ?>
                    <th>التاريخ</th>
                </tr>

                <?php
                if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }
                if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }

                if($Users->ROLES === "USER"){
                    $Row = $database->prepare("SELECT * FROM dzmyc_post WHERE USERID = :USERID "); 
                    $Row->bindparam("USERID",$Users->ID); 
                    $Row->execute();	 
                }elseif($Users->ROLES === "ADMIN"){
                    $Row = $database->prepare("SELECT * FROM dzmyc_post ");
                    $Row->execute();  }                        	

                $all = $Row->rowCount();

                $start = $per_page * $page - $per_page; $pages = ceil($all / $per_page);

                if($Users->ROLES === "USER"){   
                    $Show = $database->prepare("SELECT * FROM dzmyc_post WHERE USERID = :USERID AND DATE != '12-01-2021' ORDER BY ALERT DESC LIMIT $start,$per_page");
                    $Show->bindparam("USERID",$Users->ID); $Show->execute();
                }elseif($Users->ROLES === "ADMIN"){
                    $Show = $database->prepare("SELECT * FROM dzmyc_post WHERE DATE != '12-01-2021' ORDER BY ID DESC LIMIT $start,$per_page");
                    $Show->execute(); } ?>

                <div class="titleC" style="margin-top: 15px;" ><p>إحصائيات المواضيع [<?php echo $Show->rowCount() ?>]</p></div><!--- title --->

                <?php foreach($Show AS $JQ){ ?>
                <tr>                            
                    <th><?php
                    if($JQ['ACCEPTABLE'] == true){
                        echo "<span style='background-color: seagreen;'></span>";

                    }elseif($JQ['ACCEPTABLE'] == false){ 
                        echo "<span style='background-color: red;'></span>";
                    } ?>                                
                    <a href="../show.php?post=<?php echo $JQ['ID'] ?>" ><?php echo substr($JQ['TITLE'],0 ,150) ?> ...</a></th>                            
                    <th><?php echo $JQ['VIEW'] ?></th>
                    <th><?php echo $JQ['ALERT'] ?></th>
                    <th><a href="?edit=<?php echo $JQ['ID'] ?>" >عـدل</a></th>

                    <?php if($Users->ROLES === "ADMIN"){ 
                        $ShowUn = $database->prepare("SELECT * FROM dzmyc_users WHERE ID = :ID ");
                        $ShowUn->bindparam("ID",$JQ['USERID']); $ShowUn->execute(); $UserName = $ShowUn->fetchObject();
                        echo"<th><a href='../profile.php?id=".$UserName->ID."' >".$UserName->USERNAME."</th></a>"; } ?>
                        
                    <th><?php echo $JQ['DATE'] ?>
                        <input name="check[]" value="<?php echo $JQ["ID"] ?>" type="checkbox" />
                    </th>
                </tr>
                <?php }//end foreach ?> 

            </table>

        </form><!--- end form --->

        <div class="pages" >  
            <?php if($pages > $page AND $page > 0){ ?><a style="background-color:  #000000;" href="?page=<?php echo ($page+1) ?>" >المزيد</a>
            <?php }if($page > 1 AND $page < ($pages + 1)){  ?><a style="background-color: #333333;" href="?page=<?php echo ($page-1) ?>" >الرجوع</a>
            <?php }if($page < 0 OR !intval($page) OR $page > $pages ){ echo "<div class='error' >لا يوجد ما  تبحث عنه في هذا الرابط !</div>"; } ?>
        </div><!--- END CLASS PAGES --->
                
    </div><!--- content --->

    <?php require_once 'footer.php'; ?>

<?php

}else{
    header("location:index.php",true);
    
}
}catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>