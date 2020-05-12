 <template>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-aside  sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigation">
                <vmenu>
                    <template v-for="item in menuitems">
                        <li class="divider mt-3 " v-if="item.title">
                            <span >{{item.name}}</span>
                        </li>
                        <vsub-menu v-if="item.child" :title="item.name" :icon="item.icon">
                            <vmenu-item v-for="child in item.child" :link="child.link" :icon="child.icon" :key="child.name">{{child.name}}</vmenu-item>
                        </vsub-menu>
                        <vmenu-item v-if="item.link" :link="item.link" :icon="item.icon">
                            <span v-if="item.name == 'Dashboard'">{{$store.state.language.dashboard.dashboard}}</span>
                            <span v-if="item.name == 'Booking'">{{$store.state.language.targets.booking}}</span>
                            <span v-if="item.name == 'Targets'">{{$store.state.language.bookings.targets}}</span>
                            <span v-if="item.name == 'Finance'">{{$store.state.language.finance.finance}}</span>
                            <span v-if="item.name == 'Notifications'">{{$store.state.language.notifications.notifications}}</span>
                            <span v-if="item.name == 'Settings'">{{$store.state.language.dashboard.settings}}</span>
                        </vmenu-item>
                    </template>
                </vmenu>
                <!-- / .navigation -->
            </div>
            <!-- menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
</template>
<script>
import {
    vmenu,
    vmenuItem,
    vsubMenu
} from './menu';
import menu_items from "../../../../menu_admin.js";
export default {
    name: "left-side",
    components: {
        vmenu,
        vsubMenu,
        vmenuItem,
    },
    data() {
        return {
            menuitems: menu_items
        }
    }
}
</script>
<style scoped lang="scss">
@import "../../css/customvariables";
.left-aside {
    width: $left_menu_width;
    background: url(~img/sidebar.png);
    background-repeat: repeat-y;
    padding-top: 40px;
    background-size: 100% 100%;
}
.navigation {
    padding: 0;
}
.divider {
    margin-top: 10px;
    list-style-type: none;
    border-bottom:1px solid #ececec;
    padding-bottom: 6px;
}
.divider span {
    font-size: 15px;
    font-weight: 700;
    color: $divider-leftheader;
    margin: 20px 20px -15px 20px;
}
.sidebar {
    display: block;
    font-size: 14px;
    letter-spacing: 1px;
}
.content {
    display: block;
    width: auto;
    overflow-x: hidden;
    padding: 0 15px;
}
.badge-success {
    background-color: #22d69d;
}
.badge {
    padding: 0.60em 0.7em;
    border-radius: 0.75rem;
}
.nav_profile{
    border-bottom:1px solid #eee;
}
@media (max-width:1370px) {

.left-aside {
    width: 280px;
}
}
@media (max-width:1200px) {
.left-aside {
    width: 280px;
    left: -300px;
}
.left-hidden aside.left-aside {
    left: 0;
    display: block !important;
}
}
@media (max-width:992px) {
.left-hidden aside.left-aside{
    left: -300px;
}
.left-aside {
    left: 0;
    top:70px;
}
}
@media (max-width:560px) {
.left-aside {
    top:40px;
}
}
</style>
