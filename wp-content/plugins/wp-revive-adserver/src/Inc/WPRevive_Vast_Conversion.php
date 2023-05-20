<?php
namespace WPRevive\Inc;
use WPRevive\Inc\WPRevive_Vast_Db;

defined('ABSPATH') || exit;

class WPRevive_Vast_Conversion {

public function wprevive_vast_file_conversion($reviveURL,$campaign){

    $revive_vast_dbConnect = new WPRevive_Vast_Db;
    
         
    if($reviveURL){
        $feeds = simplexml_load_file($reviveURL);
        if (false === $feeds) {
            $msg = 'Not a valid URL';
            
        } else {
            $vast = '<VAST version="2.0">';
            $vast .= '<Ad id="' . $feeds->Ad['id'] .'">';
            $vast .= '<InLine>';
            $vast .= '<AdSystem>' . $feeds->Ad->InLine->AdSystem . '</AdSystem>';
            $vast .='<Description>VAST 2.0</Description>';
            $vast .='<Impression><![CDATA[' . $feeds->Ad->InLine->Impression->URL . ']]></Impression>';
            $vast .='<Creatives>';
            $vast .='<Creative AdID="' . $feeds->Ad->InLine->Video->AdID . '">';
            $vast .='<Linear>';
            $vast .='<Duration>' . $feeds->Ad->InLine->Video->Duration .'</Duration>';
            $vast .= '<TrackingEvents>';
            foreach ($feeds->Ad->InLine->TrackingEvents->Tracking as $event) {
            if($event['event'] == 'start') {
            $vast .='<Tracking event="start"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
            if($event['event'] == 'midpoint') {
            $vast .='<Tracking event="midpoint"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'firstQuartile') {
            $vast .='<Tracking event="firstQuartile"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'thirdQuartile') {
            $vast .='<Tracking event="thirdQuartile"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'complete') {
            $vast .='<Tracking event="complete"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'mute') {
            $vast .='<Tracking event="mute"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'pause') {
            $vast .='<Tracking event="pause"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'replay') {
            $vast .='<Tracking event="replay"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'fullscreen') {
            $vast .='<Tracking event="fullscreen"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'stop') {
            $vast .='<Tracking event="stop"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'unmute') {
            $vast .='<Tracking event="unmute"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
                if($event['event'] == 'resume') {
            $vast .='<Tracking event="resume"><![CDATA[' . $event->URL   .']]></Tracking>';
                }
            }
            $vast .= '</TrackingEvents>';
            $vast .= '<VideoClicks>';
            $vast .= '<ClickThrough><![CDATA[' . $feeds->Ad->InLine->Video->VideoClicks->ClickThrough->URL   .']]></ClickThrough>';
            $vast .= '</VideoClicks>';
            $vast .= '<MediaFiles>';
            $vast .= '<MediaFile delivery="' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile['delivery']   .'" bitrate="' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile['bitrate']   .'" width="' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile['width']   .'" height="' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile['height']   .'" type="' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile['type']   .'">' . $feeds->Ad->InLine->Video->MediaFiles->MediaFile->URL  .'</MediaFile>';
            $vast .= '</MediaFiles>';
            $vast .='</Linear>';
            $vast .= '</Creative>';
            if($feeds->Ad->InLine->CompanionAds){
            $vast .= '<Creative AdID="601364-Companion">'; 
            $vast .= '<CompanionAds>';
            $vast .= '<Companion width="' . $feeds->Ad->InLine->CompanionAds->Companion['width'] . '" height="' . $feeds->Ad->InLine->CompanionAds->Companion['height'] . '">';
            $vast .= '<StaticResource creativeType="' . $feeds->Ad->InLine->CompanionAds->Companion['resourceType'] .'">';
            $vast .=  $feeds->Ad->InLine->CompanionAds->Companion->Code ;
            $vast .= '</StaticResource>';
            //$vast .= '<TrackingEvents>';
            //$vast .= '<Tracking event="creativeView">' .    .'</Tracking>';
            //$vast .= '</TrackingEvents>';
            $vast .= '<CompanionClickThrough>'. $feeds->Ad->InLine->CompanionAds->Companion->CompanionClickThrough->URL .'</CompanionClickThrough>';
            $vast .= '</Companion>';
            $vast .= '</CompanionAds>';
            $vast .= '</Creative>';
            }
            $vast .= '</Creatives>';
            $vast .= '</InLine>';
            $vast .= '</Ad>';
            $vast .= '</VAST>';

          
            $vast2_File = 'reviveVast2_' . rand() .'.xml';
            
            $reviveVAST2_upload = wp_upload_dir();
            //$reviveVAST2_upload_dir = $reviveVAST2_upload['basedir'];
            $reviveVAST2_upload_dir = $reviveVAST2_upload['basedir'] . '/wprevive-vast2';
            $vast2_xml = $reviveVAST2_upload_dir . '/' . $vast2_File;

            $reviveVAST2_upload_url = $reviveVAST2_upload['baseurl'] . '/wprevive-vast2';
            $vast2_xml_url = $reviveVAST2_upload_url . '/' . $vast2_File;
            
                if($vast2_File){
                     if($reviveVAST2_upload_dir){
                        if (file_exists($vast2_xml)) {
                            $fh = fopen($vast2_xml, 'w+');
                            echo 'exists';
                            $file_saved = fwrite($fh, $vast."\n");
                      } else {
                            $fh = fopen($vast2_xml, 'w+');
                            $file_saved = fwrite($fh, $vast ."\n");
                      }
                      fclose($fh);
                     }
                }
                if($file_saved) {
                $msg = $campaign . ' Invocation code converted to VAST2 format. Filename is <b>' .$vast2_File . '</b>';
                $preview = $vast2_xml_url;
                $revive_vast_dbConnect->wprevive_vast_add_file($campaign,$vast2_xml_url);
                }
            
            
        }//feed

    }
    return array($msg,$preview);
}
}