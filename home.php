                
                <div class="home" >
                    <?php // ADS TOP SHOW
                    $ShowAdsTop = $database->prepare("SELECT * FROM dzmyc_ads WHERE PLACE = 'TOPPOSTS' ORDER BY TYPE DESC");
                    $ShowAdsTop->execute();
                    foreach($ShowAdsTop AS $AdsTop){
                        
                        if($AdsTop['TYPE'] == "50"){ ?>

                            <span class="ads_50" title="<?php echo $AdsTop['TITLE'] ?>" >
                        
                                <div class="topic" ><?php echo $AdsTop['TEXT'] ?></div>

                            </span>

                        <?php }else if($AdsTop['TYPE'] == "100"){ ?>

                            <span class="ads_100" title="<?php echo $AdsTop['TITLE'] ?>" > 

                                <div class="topic" ><?php echo $AdsTop['TEXT'] ?></div>

                            </span>

                        <?php }                        
                    } ?>
                </div><!--- home --->
                
                <?php // statistics
                if(!isset($_SESSION['user']) AND !isset($_SESSION['mail']) AND !isset($_SESSION['pass'])){                    
                    
                    $RightNow = floor(microtime(true)); $days = 86400 * 30 ;// 86400 = 1DAY
                    $dateStat = date("d-m-Y");
                    $toDoStat = $database->prepare("SELECT * FROM dzmyc_stat WHERE DATE = :DATE");
                    $toDoStat->bindparam("DATE",$dateStat); $toDoStat->execute();    
                    $Stat = $toDoStat->fetchObject();                   

                    if($toDoStat->rowCount() == 0 ){
                        $AddStatData = $database->prepare("INSERT INTO dzmyc_stat (DATE,TIME) VALUES (:DATE,:TIME)"); 
                        $AddStatData->bindparam("DATE",$dateStat);
                        $AddStatData->bindparam("TIME",$RightNow);
                        $AddStatData->execute();
    
                    }else if($toDoStat->rowCount() == 1 ){
                        $updateStatData = $database->prepare("UPDATE dzmyc_stat SET VIEW = :VIEW WHERE DATE = :DATE ");
                        $addViewStat = $Stat->VIEW + 1; $updateStatData->bindparam("VIEW",$addViewStat);
                        $updateStatData->bindparam("DATE",$dateStat);
                        $updateStatData->execute(); $endStat = $Stat->TIME - $days;
                        $removStatData = $database->prepare("DELETE FROM dzmyc_stat WHERE TIME < :TIME ");
                        $removStatData->bindparam("TIME",$endStat);
                        $removStatData->execute();
                    }                   

                }//End statistics 
                
                // LATEST POSTS
                $LATESTPosts = $database->prepare("SELECT * FROM dzmyc_post WHERE ACCEPTABLE = '1' ORDER BY ID DESC LIMIT 0,10 ");
                $LATESTPosts->execute();
                if($LATESTPosts->rowCount() > 0){ ?>

                <div class="titleR" ><p><img src="img/post.png" >آخر المواضيع المضافة</p></div><!--- title --->  

                <div class="home" >

                   <?php foreach($LATESTPosts AS $LATEST){  ?>
                    <div class="post" >

                        <div class="image" ><a href="show.php?post=<?php echo $LATEST['ID'] ?>" ><img src="https://i.ytimg.com/vi/<?php echo $LATEST['URLVIDEO'] ?>/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw" /></a></div><!--- image --->
                        <div class="info" >
                            <div class="title" ><a href="show.php?post=<?php echo $LATEST['ID'] ?>" ><?php echo $LATEST['TITLE'] ?></a></div><!--- title --->
                            <?php $ShowUser = $database->prepare("SELECT * FROM dzmyc_users WHERE ID = :ID ");
                                  $ShowUser->bindParam("ID",$LATEST['USERID']); $ShowUser->execute(); $UserP = $ShowUser->fetchObject(); ?>
                            <div class="topic" style="font-size:8pt;" ><p><?php echo $LATEST['DATE'] ?> | <a href="profile.php?id=<?php echo $LATEST['USERID'] ?>" ><?php echo $UserP->NAME ?></a></p></div><!--- topic --->
                        </div>

                    </div><!--- post --->
                    <?php }/* end posts post */ ?>

                </div><!--- home --->

                <?php }// End
                
                // class Posts
                $ShowClsPost = $database->prepare("SELECT * FROM dzmyc_class WHERE ROLES = 'POST' ORDER BY VIEW ASC LIMIT 0,$Admin->NUMBERCLASS ");
                $ShowClsPost->execute(); 
                if($ShowClsPost->rowCount() > 0){ ?>

                <div class="titleR" ><p><img src="img/caty.png" >تصنيف المواضيع</p></div><!--- title --->

                <div class="home" >   

                    <?php   foreach($ShowClsPost AS $ClsPost){  ?>
                    <div class="galerry" >
                        <a href="search.php?class=<?php echo $ClsPost['ID'] ?>" ><img src="<?php echo $ClsPost['PICTURE'] ?>" /><p><?php echo $ClsPost['TITLE'] ?></p></a>                                               
                    </div><!--- galerry --->                 
                    <?php }/* end galerry class */ ?>

                </div><!--- home --->
                <?php }//Show Class 
                
                // LESS VIEWED POSTS
                $LESSPosts = $database->prepare("SELECT * FROM dzmyc_post WHERE ACCEPTABLE = '1' ORDER BY VIEW ASC LIMIT 0,10 ");
                $LESSPosts->execute();
                if($LESSPosts->rowCount() > 0){ ?>

                <div class="titleR" ><p><img src="img/post.png" >مواضيع متنوعة</p></div><!--- title ---> 

                <div class="home" >

                    <?php foreach($LESSPosts AS $LESS){  ?>
                    <div class="post" >

                        <div class="image" ><a href="show.php?post=<?php echo $LESS['ID'] ?>" ><img src="https://i.ytimg.com/vi/<?php echo $LESS['URLVIDEO'] ?>/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw" /></a></div><!--- image --->
                        <div class="info" >
                            <div class="title" ><a href="show.php?post=<?php echo $LESS['ID'] ?>" ><?php echo $LESS['TITLE'] ?></a></div><!--- title --->
                            <?php $ShowUser = $database->prepare("SELECT * FROM dzmyc_users WHERE ID = :ID ");
                                  $ShowUser->bindParam("ID",$LESS['USERID']); $ShowUser->execute(); $UserP = $ShowUser->fetchObject(); ?>
                            <div class="topic" style="font-size:8pt;" ><p><?php echo $LESS['DATE'] ?> | <a href="profile.php?id=<?php echo $LESS['USERID'] ?>" ><?php echo $UserP->NAME ?></a></p></div><!--- topic --->
                        </div>

                    </div><!--- post --->
                    <?php }/* end posts post */ ?>

                </div><!--- home --->

                <?php }//Show  ?>

                <div class="home" >

                    <?php // ADS BOTTOM SHOW
                    $ShowAdsBot = $database->prepare("SELECT * FROM dzmyc_ads WHERE PLACE = 'BOTPOSTS' ORDER BY TYPE DESC");
                    $ShowAdsBot->execute();
                    foreach($ShowAdsBot AS $AdsBot){

                        if($AdsBot['TYPE'] == "50" ){ ?>

                            <span class="ads_50" title="<?php echo $AdsBot['TITLE'] ?>" > 

                                <div class='topic' ><?php echo $AdsBot['TEXT'] ?></div>

                            </span>

                        <?php }else if($AdsBot['TYPE'] == "100" ){ ?>

                            <span class="ads_100" title="<?php echo $AdsBot['TITLE'] ?>" >
                        
                                <div class="topic" ><?php echo $AdsBot['TEXT'] ?></div>

                            </span>

                        <?php }                        
                    } ?>

                </div><!--- home --->  