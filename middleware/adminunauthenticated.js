export default function ({ store, redirect, app }) {
  if (store.state.auth.loggedIn && parseInt(store.state.auth?.user?.role_id) !== 3) {
    return redirect(app.localePath("/panel/"));
  }
}