<template>
  <div>
    <!-- Vertical Nav -->
    <nav class="hk-nav hk-nav-light">
      <a
        rel="dofollow"
        href="javascript:void(0);"
        id="hk_nav_close"
        class="hk-nav-close"
        title="Close Menu"
        ><i class="fa fa-times-circle"></i
      ></a>
      <div class="nicescroll-bar">
        <div class="navbar-nav-wrap">
          <ul class="navbar-nav flex-column">
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.dashboard')"
              >
                <i class="fa fa-tachometer-alt"></i>
                <span class="nav-link-text">{{ $t("panel.dashboard") }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                :to="localePath('/panel/settings/')"
                exact
                exact-active-class="active"
                custom
                class="nav-link"
                :title="$t('panel.settings.settings')"
              >
                <i class="fa fa-cogs"></i>
                <span class="nav-link-text">{{
                  $t("panel.settings.settings")
                }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                :to="localePath('/panel/email-settings/')"
                exact
                exact-active-class="active"
                custom
                class="nav-link"
                :title="$t('panel.emailSettings.emailSettings')"
              >
                <i class="fa fa-mail-bulk"></i>
                <span class="nav-link-text">{{
                  $t("panel.emailSettings.emailSettings")
                }}</span>
              </nuxt-link>
            </li>

            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                to="javascript:void(0);"
                data-bs-toggle="collapse"
                data-bs-target="#usersnav"
                :title="$t('panel.userActions')"
              >
                <i class="fa fa-users-gear"></i>
                <span class="nav-link-text">{{ $t("panel.userActions") }}</span>
              </nuxt-link>
              <ul
                id="usersnav"
                class="nav flex-column collapse collapse-level-1"
              >
                <li class="nav-item">
                  <ul class="nav flex-column">
                    <li class="nav-item">
                      <nuxt-link
                        rel="dofollow"
                        class="nav-link"
                        exact
                        exact-active-class="active"
                        :to="localePath('/panel/user-roles/')"
                        custom
                        :title="$t('panel.userRoles.userRoles')"
                      >
                        {{ $t("panel.userRoles.userRoles") }}
                      </nuxt-link>
                    </li>
                    <li class="nav-item">
                      <nuxt-link
                        rel="dofollow"
                        class="nav-link"
                        exact
                        exact-active-class="active"
                        :to="localePath('/panel/users/')"
                        custom
                        :title="$t('panel.users.users')"
                      >
                        {{ $t("panel.users.users") }}
                      </nuxt-link>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/menus/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.menus.menus')"
              >
                <i class="fa fa-list"></i>
                <span class="nav-link-text">{{ $t("panel.menus.menus") }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/categories/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.categories.categories')"
              >
                <i class="fa fa-folder-open"></i>
                <span class="nav-link-text">{{
                  $t("panel.categories.categories")
                }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/slides/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.slides.slides')"
              >
                <i class="fa fa-images"></i>
                <span class="nav-link-text">{{
                  $t("panel.slides.slides")
                }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/pages/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.pages.pages')"
              >
                <i class="fa fa-file"></i>
                <span class="nav-link-text">{{ $t("panel.pages.pages") }}</span>
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                rel="dofollow"
                class="nav-link"
                :to="localePath('/panel/polls/')"
                exact
                exact-active-class="active"
                custom
                :title="$t('panel.polls.polls')"
              >
                <i class="fa fa-question-circle"></i>
                <span class="nav-link-text">{{ $t("panel.polls.polls") }}</span>
              </nuxt-link>
            </li>
          </ul>
          <hr class="nav-separator" />
          <div class="nav-header">
            <span>{{ $t("panel.viewOurSite") }}</span>
            <span>{{
              $t("panel.viewOurSite")
                .split(" ")
                .map(function (s) {
                  return s.charAt(0);
                })
                .join("")
            }}</span>
          </div>
          <ul class="navbar-nav flex-column">
            <li class="nav-item">
              <a
                rel="dofollow"
                class="nav-link"
                :title="$t('panel.viewSite')"
                :href="localePath('/')"
              >
                <i class="fa fa-external-link"></i>
                <span class="nav-link-text">{{ $t("panel.viewSite") }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
    <!-- /Vertical Nav -->
  </div>
</template>