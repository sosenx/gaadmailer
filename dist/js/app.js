var Gmailer_Dashboard = {
  template: '#templates-dasboard',
   
  props: [],
  
  data: function(){
    return {}
  },
  
  created: function(){},
    
  mounted: function(){},
   
  methods : {}
};
var Gmailer_UploadCSV = { 
  template: '#templates-upload-csv',
   
  props: [],
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      

  mounted: function(){},
   
  methods : {}
};
var Gmailer_Filters = { 
  template: '#templates-filters',
   
  props: [],
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      
  mounted: function(){},
   
  methods : {}
};
var Gmailer_Templates = { 
  template: '#templates-templates',
   
  props: [], 
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      
  mounted: function(){},
   
  methods : {}
};
var Gmailer_Archives = { 
  template: '#templates-archives',
   
  props: [],
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      
  mounted: function(){},
   
  methods : {}
};
var Gmailer_Outbox = { 
  template: '#templates-outbox',
   
  props: [],
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      
  mounted: function(){},
   
  methods : {}
};
var Gmailer_Send = { 
  template: '#templates-send',
   
  props: [],
   
  data: function(){
    return {} 
  },
  
  created: function(){},
      
  mounted: function(){},
   
  methods : {}
};

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
var  routes = [
  { path: '/', component: Gmailer_Dashboard },  
  { path: '/upload-csv', component: Gmailer_UploadCSV },

  { path: '/filters', component: Gmailer_Filters },
  { path: '/templates', component: Gmailer_Templates },

  { path: '/archives', component: Gmailer_Archives },
  { path: '/outbox', component: Gmailer_Outbox },
  { path: '/send', component: Gmailer_Send }
  
];

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
var router = new VueRouter({
  routes: routes
});

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
var app = new Vue({
  router: router
}).$mount('#gmailer-app');

// Now the app has started!



