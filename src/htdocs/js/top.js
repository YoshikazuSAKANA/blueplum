let _TD = '2019,6,22';
setInterval('showTimeLimit(' + _TD + ')',1000);

function showClock() {
    // 現在時刻を取得
    let nowTime = new Date();

    // 時を抜き出す
    let nowHour = nowTime.getHours();

    // 分を抜き出す
    let nowMin = nowTime.getMinutes();

    // 秒を抜き出す
    let nowSec = nowTime.getSeconds();

    let msg = "現在時刻は、" + nowHour + ":" + nowMin + ":" + nowSec;

    document.getElementById("RealTimeClockArea") . innerHTML = msg;
}

function showTimeLimit(tYear, tMonth, tDay) {
    // 現在時刻を数値に変換
    let nowTime = new Date();
    dnumNow = nowTime.getTime();

    // 納期
    let targetDay = tYear + "/" + tMonth + "/" + tDay

    // 指定日時を数値に変換
    let targetTime = new Date(targetDay);
    dnumTarget = targetTime.getTime();

    let diffMSec = dnumTarget - dnumNow;
    let diffHour = diffMSec / (1000 * 60 * 60);
    let diffDay = diffMSec / (1000 * 60 * 60 * 24);

    let limitDay = Math.floor(diffDay);
    let limitHour = diffHour - (limitDay * 24);
    let limitMin = (limitHour - Math.floor(limitHour)) * 60;
    let limitSec = (limitMin - Math.floor(limitMin)) * 60;

    limitHour = Math.floor(limitHour);
    limitMin = Math.floor(limitMin);
    limitSec = Math.floor(limitSec);

    let msg = targetDay + " まであと" + limitDay + "日" + limitHour + "時間" + limitMin + "分" + limitSec + "秒";
    document.getElementById("LimitTimeArea") . innerHTML = msg;
}
