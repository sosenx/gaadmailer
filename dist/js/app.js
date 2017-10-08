// 1. Define route components.
// These can be imported from other files
let Foo = { template: '<div>foo</div>' }
let Bar = { template: '<div>bar</div>' }

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
let routes = [
  { path: '/', component: Gmailer_Dashboard },
  { path: '/bar', component: Bar }
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
let router = new VueRouter({
  routes // short for `routes: routes`
})

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
let app = new Vue({
  router
}).$mount('#gmailer-app')

// Now the app has started!



