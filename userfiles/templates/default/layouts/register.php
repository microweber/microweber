<?php

/*

  type: layout
  content_type: static
  name: Register
  description: User registration layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>


<div class="container edit" id="home-top">

<div class="span12 box_wrapper">
                <div class="span12 box">
                    <div>
                        <div class="head">
                            <h4>Create your account</h4>
                        </div>
                        <div class="form">
                            <form>
                                <input type="text" placeholder="Email">
                                <input type="password" placeholder="Password">
                                <input type="password" placeholder="Confirm Password">
                                <input type="submit" class="btn" value="Sign up">
                            </form>
                        </div>
                    </div>
                </div>
                <p class="already">Already have an account?
                    <a href="signin.html">Sign in</a></p>
            </div>

</div>










<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
