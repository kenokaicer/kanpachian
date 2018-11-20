<div style="display: inline-block;" id="intro">
    <div class="large-12 columns">
        <img class="event-top-img round-image" style="max-width:30vw" src="<?=IMG_PATH.$event->getImage()?>" alt="mockup" />
    </div>
</div>

<div id="pricing" style="background-color: #fafafa;position:relative;top:-10px">
    <div class="login-box" style="background-color: #f6f6f6;width:65%;margin:0 auto;padding-bottom:20px">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="text-center color-pink headings" style="padding:0px;margin:0"><?=$event->getEventName()?></h2>
                <h5 class="color-pink" style="margin-bottom:15px"><?=$event->getCategory()->getCategoryName()?></h5>
            </div>
            <div class="large-12 columns"><p><?=$event->getDescription()?></p></div>
        </div>
    </div>