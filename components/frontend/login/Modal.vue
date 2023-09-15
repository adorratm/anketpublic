<template>
  <div>
    <div
      ref="modal"
      class="modal fade"
      id="sign-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div>
              <button
                ref="closeButton"
                type="button"
                class="close pl-buttons"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true"><i class="fa fa-times"></i></span>
              </button>
              <h4 class="modal-title" id="myModalLabel">
                {{ $t("login.login") }}
              </h4>
              <p>{{ $t("login.loginToManageYourAccount") }}</p>
              <hr />
            </div>

            <FrontendLoginForm />
            <hr />
            <div class="donot">
              {{ $t("login.dontHaveAccount") }}
              <nuxt-link
                rel="dofollow"
                :title="$t('login.register')"
                :to="localePath('/register')"
                >{{ $t("login.register") }}</nuxt-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>