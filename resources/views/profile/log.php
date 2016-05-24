<?php
use Loli\Uri;
use Loli\Route;
use Loli\Locale;


$this->layout('vue-template', function($parent) {
?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?=Locale::translate('Operating system')?></th>
                    <th><?=Locale::translate('Application')?></th>
                    <th><?=Locale::translate('Type')?></th>
                    <th><?=Locale::translate('IP')?></th>
                    <th><?=Locale::translate('Created')?></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="result in results">
                    <td>{{result.user_agent | parseOperatingSystem}}</td>
                    <td>{{result.user_agent | parseApplication}}</td>
                    <td>{{result.type | capitalize}}</td>
                    <td>{{result.ip}}</td>
                    <td>{{result.created_diff}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clearfix">
        <ul class="pagination pull-right">
            <li v-for="item in paginator.items" :class="{paginate_button:true, active:item.active, disabled:!item.uri}">
                <a :href="item.uri" v-link="item.uri" v-if="item.uri">{{{item.value}}}</a>
                <span v-else>{{{item.value}}}</span>
            </li>
        </ul>
    </div>
<?php
$parent();
});


$this->layout('vue', function($parent) {
    $parent();
?>
<style>
.pagination {
    margin-top:0;
    margin-bottom:1em;
}
</style>

<script type="text/javascript">
Vue.filter('parseOperatingSystem', function(userAgent) {
    if (!userAgent) {
        return 'Unknown';
    }
    var array = [
        ['Windows Phone', /Windows\s+Phone(?:.+OS\s+)?\s+(\d+(?:\.\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['Windows Phone', /Windows\s+Phone/i],

        ['Windows 2000', /Windows\s+NT\s+5\.01?(?:\s+|\)|;|$)/i],
        ['Windows XP', /Windows\s+XP(?:\s+|\)|;|$)/i],
        ['Windows XP', /Windows\s+NT\s+5\.1(?:\s+|\)|;|$)/i],
        ['Windows XP', /Windows\s+NT\s+5\.2(?:\s+|\)|;|$)/i],
        ['Windows Server 2003', /Windows\s+NT\s+5\.2\.3790(?:\s+|\)|;|$)/i],
        ['Windows Vista', /Windows\s+NT\s+6\.0(?:\s+|\)|;|$)/i],
        ['Windows 7', /Windows\s+NT\s+6\.1(?:\s+|\)|;|$)/i],
        ['Windows 8', /Windows\s+NT\s+6\.2(?:\s+|\)|;|$)/i],
        ['Windows 8.1', /Windows\s+NT\s+6\.3(?:\s+|\)|;|$)/i],
        ['Windows 10', /Windows\s+NT\s+10\.0(?:\s+|\)|;|$)/i],
        ['Windows', /Windows/i],



        ['Android %1', /Android\s+(\d+(?:\.\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['Android', /Android/i],





        ['iPhone OS %1', /iPhone.+OS\s+(\d+(?:[._]\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['iPhone OS', /iPhone/i],

        ['iPad OS %1', /iPad.+OS\s+(\d+(?:[._]\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['iPad OS ', /iPad/i],

        ['iPod OS %1', /iPod.+OS\s+(\d+(?:[._]\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['iPod OS ', /iPod/i],

        ['Mac OS X %1', /Mac\s+OS\s+X\s+v?(\d+(?:[._]\d+)?)(?:\.|_|\s+|\)|;|$)/i],
        ['Mac OS', /Mac\s+OS/i],




        ['BlackBerry %1', /BlackBerry\s*(\d+)/i],
        ['BlackBerry', /BlackBerry/i],


        ['Chrome OS', /CrOS/i],
        ['Ubuntu', /Ubuntu/i],
        ['Mint Linux', /Mint\//i],
        ['Arch Linux', /Arch\//i],
        ['Fedora', /Fedora/i],
        ['Gentoo', /Gentoo/i],
        ['FreeBSD', /FreeBSD/i],
        ['OpenBSD', /OpenBSD/i],
        ['NetBSD', /NetBSD/i],


        ['Linux', /Linux/i],
        ['Unix', /Unix/i],
        ['BSD', /BSD/i],
    ];

    for (var i = 0; i < array.length; i++) {
        var matches = userAgent.match(array[i][1]);
        if (matches) {
            var res = array[i][0];
            for (var id = 0; id < matches.length; id++) {
                res = res.replace('%' + id.toString(), matches[id].replace(/_|\-/g, '.'));
            }
            return res;
        }
    }
    return 'Unknown';
});

Vue.filter('parseApplication', function(userAgent) {
    if (!userAgent) {
        return 'Unknown';
    }
    var array = [

        ['QQBrowser %1', /QQBrowser(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['QQBrowser', /QQBrowser|MQQBrowser|TencentTraveler/i],


        ['WeChat %1', /MicroMessenger(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['WeChat', /MicroMessenger/i],

        ['QQ %1', /QQ(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],


        ['TaoBrowser %1', /TaoBrowser(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['TaoBrowser', /TaoBrowser/i],

        ['UBrowser %1', /UBrowser(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['UBrowser', /UBrowser|UCWEB/i],


        ['BaiduBrowser %1', /baidubrowser(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['BaiduBrowser', /baidubrowser/i],
        ['BaiduBrowser', /bdbrowser/i],

        ['Maxthon %1', /Maxthon(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['Maxthon', /Maxthon/i],

        ['2345Browser %1', /\s+2345\/(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['2345Browser %1', /(?:2345Browser|2345Explorer)(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['2345Browser', /2345Browser|2345Explorer/i],


        ['The world', /The\s*World/i],

        ['SogouBrowser %1', /SogouMobileBrowser(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['SogouBrowser', /MetaSr|Sogou/i],

        ['LieBaoFast %1', /LieBaoFast(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['LieBaoFast', /ACHEETAHI/i],
        ['LieBaoFast', /LBBROWSER/i],

        ['IE %1', /(?:rv\:|MSIE\s+)(\d+(?:\.\d+)?)[a-z]?(?:\.|\s+|\)|;|$)/i],

        ['Edge %1', /Edge(?:\/|\s+)(\d+(?:\.\d+)?)(?:\.|\s+|\)|;|$)/i],
        ['Edge', /Edge/i],

        ['Firefox %1', /Firefox(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['Firefox', /Firefox/i],

        ['Navigator %1', /Navigator(?:\/|\s+)(\d+(?:\.\d+)?)(?:[a-z]|\.|\s+|\)|;|$)/i],
        ['Navigator', /Navigator/i],

        ['Opera %1', /(?:Opera|Coast)(?:\/|\s+)(\d+(?:\.\d+)?)(?:\.|\s+|\)|;|$)/i],
        ['Opera', /Opera|Coast/i],

        ['Chrome %1', /(?:Chrome|CriOS)(?:\/|\s+)(\d+(?:\.\d+)?)(?:\.|\s+|\)|;|$)/i],
        ['Chrome', /Chrome|CriOS/i],

        ['Safari %1', /Version(?:\/|\s+)(\d+(?:\.\d+)?)(?:\.|\s+|\)|;).+Safari\//i],
        ['Safari', /Safari/i],
    ];

    for (var i = 0; i < array.length; i++) {
        var matches = userAgent.match(array[i][1]);
        if (matches) {
            var res = array[i][0];
            for (var id = 0; id < matches.length; id++) {
                res = res.replace('%' + id.toString(), matches[id].replace(/_|\-/g, '.'));
            }
            return res;
        }
    }
    return 'Unknown';
});

</script>

<script type="application/vue">
return {
    data: function() {
        return {
            results: [],
            paginator: {items:[]},
        };
    },
};
</script>
<?php
});



$this->load('profile/layout');
