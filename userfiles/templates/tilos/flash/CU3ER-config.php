<?  $domain = $_GET['site'];
      $domain = base64_decode($domain);

  ?><data>
  <debug>0</debug>
  <settings>
    <folder_images><? print $domain ?>userfiles/templates/tilos/img/</folder_images>

   <!--	<background>
      <color transparent="true">0xffffff</color>

      <image use_image="true" align_to="stage" align_pos="BC" x="0" y="0">
        <url>1.jpg</url>
      </image>
    </background>-->

    <start_slide>1</start_slide>
    <auto_play>true</auto_play>

    <randomize_slides>false</randomize_slides>

    <branding align_to="stage" align_pos="TR" x="-10" y="10">
      <remove_logo_loader>true</remove_logo_loader>
      <remove_right_menu_info>false</remove_right_menu_info>
      <remove_right_menu_licence>false</remove_right_menu_licence>
    </branding>

    <camera x="0" y="0" z="0" angleX="0" angleY="0" angleZ="0" lens="12"/>

  </settings>

  <fonts>
   <!-- <file url="NeoSansStd.swf"/>  -->
  </fonts>

  <preloader type="circular" align_pos="MC" width="200" height="20" x="0" y="0" radius="20">
    <background padding="5">
      <tweenShow tint="0x2185C5" alpha="0.85" x="0" y="0"/>
      <tweenOver alpha="1"/>
      <tweenHide alpha="0"/>
    </background>

    <loader>
      <tweenShow tint="0xFFFFFF" alpha="0.8"/>
      <tweenOver tint="0xFFFFFF" alpha="1"/>
      <tweenHide tint="0xFFFFFF" alpha="0"/>
    </loader>
  </preloader>

  <controls>

    <prev_button align_pos="BR" x="-51" width="30" height="30" y="-20">
      <auto_hide time="3">true</auto_hide>

      <hide_on_transition>true</hide_on_transition>

	  <background round_corners="15,0,0,15">
        <tweenShow tint="0xffffff" alpha="0.2" scaleX="1" scaleY="1"  />
        <tweenOver tint="0xFFFFFF"  alpha=".9" />
        <tweenHide tint="0xFFFFFF"  alpha="0" />
      </background>

	  <symbol type="2" align_pos="MC" x="0" y="0">
        <tweenShow alpha="1" tint="0x2185c5" x="0" y="0" scaleX="0.3" scaleY="0.3"/>

        <tweenOver tint="0x2185c5" scaleX="0.4" scaleY="0.4"  alpha="1"/>
        <tweenHide tint="0xFFFFFF" scaleX="0.3" scaleY="0.3"  alpha="0"/>
      </symbol>
    </prev_button>

    <next_button align_pos="BR" x="-20" width="30" height="30" y="-20">
      <auto_hide time="3">true</auto_hide>
      <hide_on_transition>true</hide_on_transition>

	  <background round_corners="0,15,15,0">

        <tweenShow tint="0xffffff" alpha="0.2" x="0" y="0" scaleX="1" scaleY="1"/>
        <tweenOver tint="0xFFFFFF"  alpha=".9"/>
        <tweenHide tint="0xffffff"  alpha="0"/>
      </background>

      <symbol type="2" align_pos="MC" x="0" y="0">
        <tweenShow alpha="1" tint="0x2185c5" x="0" y="0" scaleX="0.3" scaleY="0.3"/>
        <tweenOver tint="0x2185c5" scaleX="0.4" scaleY="0.4"  alpha="1"/>
        <tweenHide tint="0xFFFFFF" scaleX="0.3" scaleY="0.3"  alpha="0"/>
      </symbol>

    </next_button>

  </controls>

  <description enabled="false" align_pos="ML" y="-50" width="215" height="40" x="0">

    <auto_hide time="3">true</auto_hide>
    <hide_on_transition>true</hide_on_transition>
    <bake_on_transition>false</bake_on_transition>

    <background round_corners="0,0,0,0">

      <tweenShow tint="0xce320a" alpha="1" x="0" scaleX="1" scaleY="1"/>
      <tweenOver tint="0xae2205" scaleX="1" x="0" scaleY="1"  alpha="1"/>
      <tweenHide tint="0xce320a" scaleX="1" x="-210" scaleY="1"  alpha="0"/>
    </background>

    <heading margin="2,5,5,13" text_bold="true" text_size="24" text_color="0xFFFFFF" font="Neo Sans Std" x="5" y="5" width="90" text_margin="5,5,5,5" text_leading="0" text_letterSpacing="0" text_align="right">
      <tweenShow tint="0xFFFFFF" alpha="1"/>
      <tweenOver tint="0xFFFFFF"/>
      <tweenHide tint="0xFFFFFF"/>
    </heading>


    <paragraph margin="0,5,5,5" text_size="11" font="Arial" text_margin="0,5,5,5" text_leading="0">
      <tweenShow tint="0x000000"/>
      <tweenOver tint="0xFFFFFF"/>
      <tweenHide tint="0xFFFFFF"/>
    </paragraph>

  </description>

  <thumbnails enabled="false" align_pos="BL" x="0" width="228" height="62" padding_x="2" padding_y="8" y="4">
    <auto_hide time="3">true</auto_hide>

    <hide_on_transition>false</hide_on_transition>

    <background color="0x333333" alpha="0" round_corners="5,5,5,5"/>

    <thumb width="30" height="30" spacing_x="3" spacing_y="0">

      <background round_corners="15,15,15,15">
        <tweenShow tint="0xffffff" alpha=".2"/>
        <tweenOver tint="0xffffff" alpha=".9"/>
        <tweenHide tint="0xFFFFFF" alpha="0"/>
        <tweenSelected tint="0x2185c5" alpha="1"/>

      </background>

      <title x="9" y="5" width="20" height="20" text_align="left" text_size="12" use_numbering_separator="" font="Neo Sans Std" use_numbering="true" text_letterSpacing="0" text_leading="0">

        <tweenShow tint="0x2185c5" x="0" y="0" alpha="1"/>
        <tweenOver tint="0x2185c5" alpha=".7"/>
        <tweenHide tint="0xFFFFFF"/>
        <tweenSelected tint="0xffffff" alpha="1"/>
      </title>

    </thumb>
  </thumbnails>


  <defaults>

    <slide time="5" color="0x000000">
      <image align_pos="MC" x="0" y="0" scaleX="1" scaleY="1"/>
      <link target="_blank"/>
      <description>
        <link target="_blank"/>
      </description>
    </slide>

    <transition
		type="3D"
		columns="5"
		rows="3"
		type2D="slide"
		flipAngle="180"
		flipOrder="45"
		flipShader="flat"
		flipOrderFromCenter="false"
		flipDirection="down"
		flipColor="0x463a00"
		flipBoxDepth="20"
		lipDepth="180"
		flipEasing="Sine.easeOut"
		flipDuration=".8"
		flipDelay=".15"
		flipDelayRandomize=".5"
	/>


  </defaults>

  <slides width="952" height="420" align_pos="TC" x="0" y="0">
    <slide>
      <url>h_1.jpg</url>
      <link target="_self"/>


      <image x="0" y="0" scaleX="1" scaleY="1" align_pos="MC"/>
    </slide>

    <transition flipDepth="300" flipAngle="180" flipDirection="right,left" rows="1" flipOrder="0" flipOrderFromCenter="false" flipDelay="0.15"/>

	<slide>
      <url>h_2.jpg</url>
      <link target="_blank"/>

      <image x="0" y="0" scaleX="1" scaleY="1" align_pos="MC"/>
    </slide>

	<transition flipDepth="600" flipDelay="0.5" flipDuration="0.8" rows="1" columns="5" flipAngle="180" flipDirection="down" flipOrder="0" flipOrderFromCenter="true" type="3D"/>

	<slide color="0xebe7a8">
      <url>h_3.jpg</url>
      <link target="_blank"/>

      <image x="0" y="0" scaleX="1" scaleY="1" align_pos="MC"/>
    </slide>

    <transition flipBoxDepth="7" flipAngle="180" type="3D" flipOrder="0" flipOrderFromCenter="true" flipDirection="up,down"/>

    <slide>
      <url>h_4.jpg</url>
      <link target="_blank"/>


      <image x="0" y="0" scaleX="1" scaleY="1" align_pos="MC"/>
    </slide>

	<transition columns="1" rows="6" flipDepth="400"/>





  </slides>

  <background>
    <color transparent="true">0xffffff</color>
  </background>
</data>