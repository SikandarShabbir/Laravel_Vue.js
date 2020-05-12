
var admin = "/admin";
const layout = [

	{
		path: admin+'/dashboard',
		component: resolve => require(['admin/index'], resolve),
		meta: {
			title: "Dashboard",
			isLoggedIn: true,
		}
	}, {
		path: admin+'/booking',
		component: resolve => require(['admin/booking'], resolve),
		meta: {
			title: "Booking",
			isLoggedIn: true,
		}
	}, {
		path: admin+'/targets',
		component: resolve => require(['admin/targets'], resolve),
		meta: {
			title: "Targets",
			isLoggedIn: true,
		}
	}, {
		path: admin+'/finance',
		component: resolve => require(['admin/finance'], resolve),
		meta: {
			title: "Finance",
			isLoggedIn: true,
		}
	}, {
		path: admin+'/notifications',
		component: resolve => require(['admin/notifications'], resolve),
		meta: {
			title: "Notifications",
			isLoggedIn: true,
		}
	}, {
		path: admin+'/settings',
		component: resolve => require(['admin/settings'], resolve),
		meta: {
			title: "Settings",
			isLoggedIn: true,
		}
	}, {
	    path: admin+'/site_configuration',
	    component: resolve => require(['admin/site_configuration'], resolve),
	    meta: {
	        title: "Site Configuration",
	    }
	}, {
	    path: admin+'/vue-datepicker',
	    component: resolve => require(['admin/blank'], resolve),
	    meta: {
	        title: "Datepickers",
	    }
	}, {
	    path: admin+'/modals',
	    component: resolve => require(['admin/blank'], resolve),
	    meta: {
	        title: "Modals",
	    }
	}, {
	    path: admin+'/blank',
	    component: resolve => require(['admin/blank'], resolve),
	    meta: {
	        title: "Blank",
	    }
	}, {
	    path: admin+'/tasks',
	        component: resolve => require(['admin/blank'], resolve),
	        meta: {
	        title: "tasks",
	    }
	}]

export default layout