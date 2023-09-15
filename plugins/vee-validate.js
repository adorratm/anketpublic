import { extend } from "vee-validate";
import * as rules from "vee-validate/dist/rules";
import { messages } from 'vee-validate/dist/locale/tr.json';

Object.keys(rules).forEach(rule => {
    extend(rule, {
        ...rules[rule], // copies rule configuration
        message: messages[rule] // assign message
    });
});

extend('url', {
    validate(value) {
      if (value) {
        return /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(value);
      }
  
      return false;
    },
    message: 'Geçersiz URL',
  })