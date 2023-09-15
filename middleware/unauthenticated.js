export default function ({ store, redirect, app }) {
  if (store.state.auth.loggedIn) {
    return redirect(app.localePath("/"));
  }
}