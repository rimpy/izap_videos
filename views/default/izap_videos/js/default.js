/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 * 
 */

this.screenshotPreview = function(){
  xOffset = 10;
  yOffset = 30;
  $("a.screenshot").hover(function(e){
    this.t = this.title;
    this.title = "";
    var c = (this.t != "") ? "<br/>" + this.t : "";
    $("body").append("<p id='screenshot'><img src='"+ this.rel +"' alt='url preview' />"+ c +"</p>");
    $("#screenshot").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").fadeIn("fast");
  },function(){
    $("#screenshot").remove();
  });
  $("a.screenshot").mousemove(function(e){
    $("#screenshot").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
  });
};

$(document).ready(function(){
  screenshotPreview();
});
function izap_vid(baseurl,eid, vid){
  url = baseurl+'pg/view/'+eid+'/izap_load/'+vid+'?shell=no&username=admin&context=dashboard&callback=true';
  wid = '#widgetcontent'+eid;
  $(wid).html('<div align="center" class="ajax_loader"></div>');
  $(wid).load(url);
}