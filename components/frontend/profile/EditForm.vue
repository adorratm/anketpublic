<template>
  <div class="pl-signup" id="pl-signup">
    <h4 class="pl-page-head">
      {{ $t("profile.profile") }}
      <i class="fas fa-key"></i>
    </h4>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form class="pl-form" @submit.prevent="handleSubmit(updateProfile)">
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="first_name"
                :name="$t('profile.firstName')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.firstName") }}</b> <b class="red">*</b>
                <label for="first_name">
                  <i class="fa fa-user"></i>
                  <input
                    id="first_name"
                    type="text"
                    v-model="formData.first_name"
                    :placeholder="$t('profile.firstName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="last_name"
                :name="$t('profile.lastName')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.lastName") }}</b> <b class="red">*</b>
                <label for="last_name">
                  <i class="fa fa-user"></i>
                  <input
                    id="last_name"
                    type="text"
                    v-model="formData.last_name"
                    :placeholder="$t('profile.lastName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="gender"
                rules="required"
                :name="$t('profile.gender')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.gender") }}</b> <b class="red">*</b>
                <label for="gender">
                  <i class="fa fa-venus-mars"></i>
                  <v-select
                    name="gender"
                    id="gender"
                    v-model="formData.gender"
                    :options="genders"
                    label="gender"
                    :deselectFromDropdown="true"
                    @option:deselected="formData.gender = null"
                    @clear="formData.gender = null"
                    :placeholder="$t('profile.gender')"
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="birth_date"
                :name="$t('profile.birthDate')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.birthDate") }}</b> <b class="red">*</b>
                <label for="birth_date">
                  <i class="fa fa-cake"></i>
                  <input
                    id="birth_date"
                    :placeholder="$t('profile.birthDate')"
                    type="date"
                    v-model="formData.birth_date"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="username"
                :name="$t('profile.yourUsername')"
                rules="required|min:2|alpha_num|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.yourUsername") }}</b> <b class="red">*</b>
                <label for="username">
                  <i class="fa fa-user"></i>
                  <input
                    id="username"
                    type="text"
                    v-model="formData.username"
                    :placeholder="$t('profile.yourUsername')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="phone"
                :name="$t('profile.phone')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.phone") }}</b> <b class="red">*</b>
                <label for="phone">
                  <i class="fa fa-mobile"></i>
                  <input
                    id="phone"
                    type="text"
                    v-model="formData.phone"
                    :placeholder="$t('profile.phone')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="city"
                :name="$t('profile.city')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.city") }}</b> <b class="red">*</b>
                <label for="city">
                  <select
                    v-model="formData.city"
                    name="city"
                    id="city"
                    @change.prevent="
                      getCities(formData.city);
                      formData.district = null;
                      formData.neighborhood = null;
                      formData.quarter = null;
                    "
                  >
                    <option :value="null" :selected="formData.city === null">
                      {{ $t("profile.pleaseSelectCity") }}
                    </option>
                    <option
                      :value="item.il_adi"
                      :key="index"
                      v-for="(item, index) in cities"
                      :selected="item.il_adi === formData.city"
                    >
                      {{ item.il_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="district"
                :name="$t('profile.district')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.district") }}</b> <b class="red">*</b>
                <label for="district">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="formData.district"
                    name="district"
                    id="district"
                    @change.prevent="
                      getDistricts(formData.district);
                      formData.neighborhood = null;
                      formData.quarter = null;
                    "
                  >
                    <option :value="null">
                      {{ $t("profile.pleaseSelectDistrict") }}
                    </option>
                    <option
                      :value="item.ilce_adi"
                      :key="index"
                      v-for="(item, index) in districts"
                      :selected="item.ilce_adi === formData.district"
                    >
                      {{ item.ilce_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="neighborhood"
                :name="$t('profile.neighborhood')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.neighborhood") }}</b>
                <label for="neighborhood">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="formData.neighborhood"
                    name="neighborhood"
                    id="neighborhood"
                    @change.prevent="
                      getNeighborhoods(formData.neighborhood);
                      formData.quarter = null;
                    "
                  >
                    <option
                      :data-id="null"
                      :value="null"
                      :selected="formData.neighborhood === null"
                    >
                      {{ $t("profile.pleaseSelectNeighborhood") }}
                    </option>
                    <option
                      :value="item.semt_adi"
                      :key="index"
                      v-for="(item, index) in neighborhoods"
                      :selected="item.semt_adi === formData.neighborhood"
                      :data-id="item.semt_id"
                    >
                      {{ item.semt_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="quarter"
                :name="$t('profile.quarter')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.quarter") }}</b>
                <label for="quarter">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="formData.quarter"
                    name="quarter"
                    id="quarter"
                  >
                    <option :value="null" :selected="formData.quarter === null">
                      {{ $t("profile.pleaseSelectQuarter") }}
                    </option>
                    <option
                      :value="item.mahalle_adi"
                      :key="index"
                      v-for="(item, index) in quarters"
                      :selected="item.mahalle_adi === formData.quarter"
                    >
                      {{ item.mahalle_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-group-inp mt-2">
          <ValidationProvider
            vid="address"
            :name="$t('profile.address')"
            rules="min:2"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("profile.address") }}</b> <b class="red">*</b>
            <label for="address">
              <i class="fa fa-map-marker-alt"></i>
              <textarea
                id="address"
                type="text"
                v-model="formData.address"
                :placeholder="$t('profile.address')"
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-group-inp mt-2">
          <ValidationProvider
            vid="profileEmail"
            :name="$t('profile.yourEmailAddress')"
            rules="required|min:2|email|max:255"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("profile.yourEmailAddress") }}</b> <b class="red">*</b>
            <label for="profileEmail">
              <i class="fa fa-envelope-open"></i>
              <input
                id="profileEmail"
                type="email"
                v-model="formData.email"
                :placeholder="$t('profile.yourEmailAddress')"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="profilePassword"
                :name="$t('profile.yourPassword')"
                rules="min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.yourPassword") }}</b> <b class="red">*</b>
                <label for="profilePassword">
                  <i class="fa fa-lock"></i>
                  <input
                    id="profilePassword"
                    :placeholder="$t('profile.yourPassword')"
                    type="password"
                    v-model="formData.password"
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="passwordRepeat"
                :name="$t('profile.yourPasswordRepeat')"
                rules="min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.yourPasswordRepeat") }}</b>
                <b class="red">*</b>
                <label for="passwordRepeat">
                  <i class="fa fa-lock"></i>
                  <input
                    id="passwordRepeat"
                    :placeholder="$t('profile.yourPasswordRepeat')"
                    type="password"
                    v-model="formData.passwordRepeat"
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="type"
                :name="$t('profile.type')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.type") }}</b> <b class="red">*</b>
                <label for="type">
                  <i class="fa fa-user-group"></i>
                  <select
                    name="type"
                    id="type"
                    v-model="formData.type"
                    required
                  >
                    <option value="INDIVIDUAL">
                      {{ $t("profile.individual") }}
                    </option>
                    <option value="CORPORATE">
                      {{ $t("profile.corporate") }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6" v-if="formData?.type === 'CORPORATE'">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="company_name"
                :name="$t('profile.companyName')"
                rules="required|min:2|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.companyName") }}</b> <b class="red">*</b>
                <label for="company_name">
                  <i class="fa fa-building"></i>
                  <input
                    id="company_name"
                    type="text"
                    v-model="formData.company_name"
                    :placeholder="$t('profile.companyName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row" v-if="formData?.type === 'CORPORATE'">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="tax_number"
                :name="$t('profile.taxNumber')"
                rules="required|min:10|max:12"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.taxNumber") }}</b> <b class="red">*</b>
                <label for="tax_number">
                  <i class="fa fa-building"></i>
                  <input
                    id="tax_number"
                    type="text"
                    v-model="formData.tax_number"
                    :placeholder="$t('profile.taxNumber')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="tax_administration"
                :name="$t('profile.taxAdministration')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("profile.taxAdministration") }}</b>
                <b class="red">*</b>
                <label for="tax_administration">
                  <i class="fa fa-building"></i>
                  <input
                    id="tax_administration"
                    type="text"
                    v-model="formData.tax_administration"
                    :placeholder="$t('profile.taxAdministration')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="card mb-3">
              <div
                class="card-header d-flex text-center justify-content-center"
                :style="
                  formData?.cover
                    ? 'background-image:url(' +
                      $config.UPLOADS_URL +
                      '/users/' +
                      formData.cover +
                      ')'
                    : 'unset'
                "
                style="background-size: cover"
              >
                <div
                  class="card-body d-flex text-center justify-content-center"
                >
                  <img
                    v-if="formData?.picture"
                    loading="lazy"
                    :data-src="
                      $config.API_URL + 'uploads/users/' + formData.picture
                    "
                    class="lazyload rounded-circle p-3 bg-light"
                    :alt="$t('panel.users.picture')"
                    width="150"
                    height="150"
                    referrerpolicy="no-referrer"
                  />
                  <i
                    v-else
                    class="fa fa-user-circle fa-5x text-center bg-light p-3 rounded-circle"
                  ></i>
                </div>
              </div>
              <div class="card-body text-center">
                <h5 class="card-title">
                  {{ $t("panel.users.pleaseSelectPicture") }}
                </h5>
                <div class="mb-3">
                  <label for="formFile" class="form-label">{{
                    $t("panel.users.pleaseSelectPicture")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formFile"
                    name="img_url"
                    @change="formData.img_url = $event.target.files[0]"
                    :placeholder="$t('panel.users.pleaseSelectPicture')"
                  />
                </div>
                <div class="mb-3">
                  <label for="formCover" class="form-label">{{
                    $t("panel.users.pleaseSelectCover")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formCover"
                    name="cover_url"
                    @change="formData.cover_url = $event.target.files[0]"
                    :placeholder="$t('panel.users.pleaseSelectCover')"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="card mb-3">
              <div class="card">
                <div class="card-header bg-light">
                  <div class="card-title mb-0">
                    <h4 class="text-center mb-0">
                      <i class="fa fa-comments"></i>
                      {{ $t("panel.users.socialMedia") }}
                    </h4>
                  </div>
                </div>
                <div class="card-body border">
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="facebook"
                      :name="$t('panel.users.facebook')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="facebook"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-facebook-square w-auto"></i>
                        </label>
                        <input
                          id="facebook"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.facebook')"
                          type="text"
                          name="facebook"
                          v-model="formData.facebook"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="twitter"
                      :name="$t('panel.users.twitter')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="twitter"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-twitter"></i>
                        </label>
                        <input
                          id="twitter"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.twitter')"
                          type="text"
                          name="twitter"
                          v-model="formData.twitter"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="instagram"
                      :name="$t('panel.users.instagram')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="instagram"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-instagram"></i>
                        </label>
                        <input
                          id="instagram"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.instagram')"
                          type="text"
                          name="instagram"
                          v-model="formData.instagram"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="youtube"
                      :name="$t('panel.users.youtube')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="youtube"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-youtube"></i>
                        </label>
                        <input
                          id="youtube"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.youtube')"
                          type="text"
                          name="youtube"
                          v-model="formData.youtube"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="linkedin"
                      :name="$t('panel.users.linkedin')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="linkedin"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-linkedin"></i>
                        </label>
                        <input
                          id="linkedin"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.linkedin')"
                          type="text"
                          name="linkedin"
                          v-model="formData.linkedin"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="reddit"
                      :name="$t('panel.users.reddit')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="reddit"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-reddit"></i>
                        </label>
                        <input
                          id="reddit"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.reddit')"
                          type="text"
                          name="reddit"
                          v-model="formData.reddit"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="medium"
                      :name="$t('panel.users.medium')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="medium"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-medium"></i>
                        </label>
                        <input
                          id="medium"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.medium')"
                          type="text"
                          name="medium"
                          v-model="formData.medium"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="pinterest"
                      :name="$t('panel.users.pinterest')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="pinterest"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa fa-pinterest"></i>
                        </label>
                        <input
                          id="pinterest"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.pinterest')"
                          type="text"
                          name="pinterest"
                          v-model="formData.pinterest"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                  <div class="form-group my-1">
                    <ValidationProvider
                      vid="tiktok"
                      :name="$t('panel.users.tiktok')"
                      rules="url|min:2"
                      v-slot="{ errors }"
                      tag="div"
                    >
                      <div
                        class="d-flex align-items-center align-content-center align-self-center"
                      >
                        <label
                          for="tiktok"
                          class="w-25p text-center d-flex align-items-center align-self-center align-content-center my-auto justify-content-center"
                        >
                          <i class="fa-brands fa-tiktok"></i>
                        </label>
                        <input
                          id="tiktok"
                          class="form-control form-control-sm rounded-0 ms-3"
                          :placeholder="$t('panel.users.tiktok')"
                          type="text"
                          name="tiktok"
                          v-model="formData.tiktok"
                        />
                      </div>
                      <small class="mt-1 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button
          ref="submitBtn"
          class="pl-buttons nbtn green-btn mt-3"
          type="submit"
          :disabled="invalid"
        >
          {{ $t("profile.updateProfile") }}
        </button>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      cities: [],
      districts: [],
      neighborhoods: [],
      quarters: [],
      selectedCity: null,
      selectedDistrict: null,
      selectedNeighborhood: null,
      selectedQuarter: null,
      genders: ["Erkek", "Kadın", "Diğer"],
      formData: {
        email: null,
        password: null,
        passwordRepeat: null,
        username: null,
        gender: null,
        birth_date: null,
        phone: null,
        first_name: null,
        last_name: null,
        city: null,
        district: null,
        neighborhood: null,
        quarter: null,
        address: null,
        type: "INDIVIDUAL",
        tax_number: null,
        tax_office: null,
        company_name: null,
        img_url: null,
        picture: null,
        cover: null,
        facebook: null,
        instagram: null,
        twitter: null,
        linkedin: null,
        youtube: null,
        reddit: null,
        tiktok: null,
        medium: null,
        pinterest: null,
      },
    };
  },
  methods: {
    getFormData(object) {
      return Object.keys(object).reduce((formData, key) => {
        if (object[key] !== null) {
          formData.append(
            key,
            Array.isArray(object[key])
              ? JSON.stringify(object[key])
              : object[key]
          );
        }
        return formData;
      }, new FormData());
    },
    /**
     * Update Profile
     */
    async updateProfile() {
      try {
        this.$refs.submitBtn.disabled = true;
        const formData = this.getFormData(this.formData);
        let { data } = await this.$axios.post("update-profile", formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        if (data.status) {
          this.$refs.submitBtn.disabled = false;
          this.$toast.success(data.message, this.$t("successfully"));
          await this.$auth.fetchUser();
          this.$router.replace(this.localePath("/profile"));
        } else {
          this.$refs.submitBtn.disabled = false;
          this.$toast.error(data.message, this.$t("unsuccessfully"));
        }
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    async getCities($city_name = null) {
      try {
        let url = "users/cities";
        if ($city_name) {
          let $params = new URLSearchParams({ city_name: $city_name });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($city_name) {
            this.selectedCity = data.data;
            await this.getDistricts();
          } else {
            this.cities = data.data;
            this.districts = [];
            this.neighborhoods = [];
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getDistricts($district_name = null) {
      try {
        let url = "users/districts/";
        if (this.selectedCity) {
          url += this.selectedCity.id + "/";
        }
        if ($district_name !== null) {
          let $params = new URLSearchParams({ district_name: $district_name });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($district_name) {
            this.selectedDistrict = data.data;
            await this.getNeighborhoods();
          } else {
            this.districts = data.data;
            this.neighborhoods = [];
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getNeighborhoods($neighborhood_name = null) {
      try {
        let url = "users/neighborhoods/";
        if (this.selectedDistrict) {
          url += this.selectedDistrict.id + "/";
        }
        if ($neighborhood_name !== null) {
          let $params = new URLSearchParams({
            neighborhood_name: $neighborhood_name,
          });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($neighborhood_name) {
            this.selectedNeighborhood = data.data;
            await this.getQuarters();
          } else {
            this.neighborhoods = data.data;
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getQuarters($quarter_name = null) {
      try {
        let url = "users/quarters/";
        if (this.selectedNeighborhood) {
          url += this.selectedNeighborhood.id + "/";
        }
        if ($quarter_name !== null) {
          let $params = new URLSearchParams({
            quarter_name: $quarter_name,
          });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($quarter_name) {
            this.selectedQuarter = data.data;
          } else {
            this.quarters = data.data;
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getProfile() {
      try {
        let { data } = await this.$axios.get("me");
        this.formData = data.data;
        this.formData.password = null;
        this.formData.permissions = null;
        await this.getCities(this.formData.city);
        await this.getDistricts(this.formData.district);
        await this.getNeighborhoods(this.formData.neighborhood);
        await this.getQuarters(this.formData.quarter);
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.getCities();
    this.getProfile();
  },
};
</script>