<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(saveUser)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row">
          <div class="col-sm-9">
            <div class="row mb-1">
              <div class="col-sm-4">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="first_name"
                    :name="$t('panel.users.firstName')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="first_name" class="mb-5">{{
                      $t("panel.users.firstName")
                    }}</label>
                    <input
                      id="first_name"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.firstName')"
                      type="text"
                      required
                      name="first_name"
                      v-model="formData.first_name"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="last_name"
                    :name="$t('panel.users.lastName')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="last_name" class="mb-5">{{
                      $t("panel.users.lastName")
                    }}</label>
                    <input
                      id="last_name"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.lastName')"
                      type="text"
                      required
                      name="last_name"
                      v-model="formData.last_name"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="username"
                    :name="$t('panel.users.username')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="username" class="mb-5">{{
                      $t("panel.users.username")
                    }}</label>
                    <input
                      id="username"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.username')"
                      type="text"
                      required
                      name="username"
                      v-model="formData.username"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="gender"
                    :name="$t('panel.users.gender')"
                    rules="required"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="gender" class="mb-5">{{
                      $t("panel.users.gender")
                    }}</label>
                    <select
                      name="gender"
                      id="gender"
                      class="form-control form-control-sm rounded-0"
                      required
                      v-model="formData.gender"
                    >
                      <option
                        :value="null"
                        :selected="formData.gender === null"
                      >
                        {{ $t("panel.users.pleaseSelectGender") }}
                      </option>
                      <option
                        :value="'Erkek'"
                        :selected="formData.gender === 'Erkek'"
                      >
                        {{ $t("panel.users.male") }}
                      </option>
                      <option
                        :value="'Kadın'"
                        :selected="formData.gender === 'Kadın'"
                      >
                        {{ $t("panel.users.female") }}
                      </option>
                      <option
                        :value="'Diğer'"
                        :selected="formData.gender === 'Diğer'"
                      >
                        {{ $t("panel.users.other") }}
                      </option>
                    </select>

                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="birth_date"
                    :name="$t('panel.users.birthDate')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="birth_date" class="mb-5">{{
                      $t("panel.users.birthDate")
                    }}</label>
                    <input
                      id="birth_date"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.birthDate')"
                      type="date"
                      required
                      name="birth_date"
                      v-model="formData.birth_date"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="city"
                    :name="$t('panel.users.city')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="city" class="mb-5">{{
                      $t("panel.users.city")
                    }}</label>
                    <select
                      class="form-control form-control-sm rounded-0"
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
                        {{ $t("panel.users.pleaseSelectCity") }}
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
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="district"
                    :name="$t('panel.users.district')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="district" class="mb-5">{{
                      $t("panel.users.district")
                    }}</label>
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
                        {{ $t("panel.users.pleaseSelectDistrict") }}
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
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="neighborhood"
                    :name="$t('panel.users.neighborhood')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="neighborhood" class="mb-5">{{
                      $t("panel.users.neighborhood")
                    }}</label>
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
                        {{ $t("panel.users.pleaseSelectNeighborhood") }}
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
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="quarter"
                    :name="$t('panel.users.quarter')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="quarter" class="mb-5">{{
                      $t("panel.users.quarter")
                    }}</label>
                    <select
                      class="form-control form-control-sm rounded-0"
                      v-model="formData.quarter"
                      name="quarter"
                      id="quarter"
                    >
                      <option
                        :value="null"
                        :selected="formData.quarter === null"
                      >
                        {{ $t("panel.users.pleaseSelectQuarter") }}
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
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-12">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="address"
                    :name="$t('panel.users.address')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="address" class="mb-5">{{
                      $t("panel.users.address")
                    }}</label>
                    <textarea
                      rows="5"
                      id="address"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.address')"
                      required
                      name="address"
                      v-model="formData.address"
                    ></textarea>
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="email"
                    :name="$t('panel.users.email')"
                    rules="required|min:2|email"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="email" class="mb-5">{{
                      $t("panel.users.email")
                    }}</label>
                    <input
                      id="email"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.email')"
                      type="email"
                      required
                      name="email"
                      v-model="formData.email"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="phone"
                    :name="$t('panel.users.phone')"
                    rules="required|min:11|max:20"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="phone" class="mb-5">{{
                      $t("panel.users.phone")
                    }}</label>
                    <input
                      id="phone"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.phone')"
                      type="tel"
                      required
                      name="phone"
                      v-model="formData.phone"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="password"
                    :name="$t('panel.users.password')"
                    :rules="{ required: id ? false : true, min: 6 }"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="password" class="mb-5">{{
                      $t("panel.users.password")
                    }}</label>
                    <input
                      id="password"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.password')"
                      type="password"
                      name="password"
                      v-model="formData.password"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="password_repeat"
                    :name="$t('panel.users.passwordRepeat')"
                    :rules="{ required: id ? false : true, min: 6 }"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="password_repeat" class="mb-5">{{
                      $t("panel.users.passwordRepeat")
                    }}</label>
                    <input
                      id="password_repeat"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.users.passwordRepeat')"
                      type="password"
                      name="password_repeat"
                      v-model="formData.password_repeat"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="role_id"
                    :name="$t('panel.users.role')"
                    rules="required"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="role_id" class="mb-5">{{
                      $t("panel.users.role")
                    }}</label>
                    <select
                      name="role_id"
                      id="role_id"
                      class="form-control form-control-sm rounded-0"
                      required
                      v-model="formData.role_id"
                      v-if="user_roles"
                    >
                      <option
                        v-for="(item, index) in user_roles"
                        :key="index"
                        :value="item.id"
                        :selected="formData.role_id === item.id"
                      >
                        {{ item.title }}
                      </option>
                    </select>

                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="type"
                    :name="$t('panel.users.type')"
                    rules="required"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="type" class="mb-5">{{
                      $t("panel.users.type")
                    }}</label>
                    <select
                      name="type"
                      id="type"
                      class="form-control form-control-sm rounded-0"
                      required
                      v-model="formData.type"
                    >
                      <option
                        value="INDIVIDUAL"
                        :selected="formData.type === 'INDIVIDUAL'"
                      >
                        {{ $t("panel.users.individual") }}
                      </option>
                      <option
                        value="CORPORATE"
                        :selected="formData.type === 'CORPORATE'"
                      >
                        {{ $t("panel.users.corporate") }}
                      </option>
                    </select>

                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="card mb-3">
              <div
                class="card-header d-flex text-center justify-content-center"
                :style="
                  formData.cover
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
                    v-if="formData.picture"
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
                  {{ formData.first_name }} {{ formData.last_name }}
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
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
                      <small class="mt-5 d-block text-danger">{{
                        errors[0]
                      }}</small>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-6">
            <div class="form-group my-1">
              <button
                class="btn btn-pink btn-sm rounded-0"
                type="submit"
                :disabled="invalid"
              >
                {{ $t("panel.save") }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  props: ["id"],
  data() {
    return {
      user_roles: [],
      cities: [],
      districts: [],
      neighborhoods: [],
      quarters: [],
      selectedCity: null,
      selectedDistrict: null,
      selectedNeighborhood: null,
      selectedQuarter: null,
      formData: {
        username: null,
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        password: null,
        password_repeat: null,
        role_id: 3,
        city: null,
        district: null,
        neighborhood: null,
        quarter: null,
        address: null,
        gender: null,
        birth_date: null,
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
    async getRoles() {
      try {
        let { data } = await this.$axios.get("panel/user-roles/");
        if (data && data.user_roles) {
          this.user_roles = data.user_roles;
        }
      } catch (error) {
        console.log(error);
      }
    },
    async saveUser() {
      try {
        const formData = this.getFormData(this.formData);
        let url = this.id
          ? "panel/users/update/" + this.id
          : "panel/users/save/";
        let { data } = await this.$axios.post(url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        if (this.id === this.$store.state.auth.user.id) {
          await this.$auth.fetchUser();
        }
        setTimeout(() => {
          if (this.id) {
            window.location.reload();
          } else {
            this.$router.replace(this.localePath("/panel/users/"));
          }
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },
    async getUser(id) {
      try {
        let { data } = await this.$axios.get("panel/users/" + id);
        if (data.user) {
          this.formData = data.user;
          this.formData.password = null;
          this.formData.password_repeat = null;
          await this.getCities(this.formData.city);
          await this.getDistricts(this.formData.district);
          await this.getNeighborhoods(this.formData.neighborhood);
          await this.getQuarters(this.formData.quarter);
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getCities($city_name = null) {
      try {
        let url = "panel/users/cities";
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
        let url = "panel/users/districts/";
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
        let url = "panel/users/neighborhoods/";
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
        let url = "panel/users/quarters/";
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
  },
  mounted() {
    this.getCities();
    if (this.id) {
      this.getUser(this.id);
    }

    this.getRoles();
  },
};
</script>