<template>
  <div class="container">
    <h1 style="text-align: center">Contacts</h1>
    <button
      type="button"
      id="newContact"
      @click="newContact()"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#modal"
    >
      New contact
    </button>
    <div
      class="modal fade"
      id="modal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="saveContact()">
            <div class="modal-header">
              <h5 class="modal-title">Contact</h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Name</label>
                  <input
                    type="text"
                    v-model="object.name"
                    class="form-control"
                    maxlength="50"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label>Email</label>
                  <input
                    type="email"
                    v-model="object.email"
                    class="form-control"
                    maxlength="50"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label>Phone</label>
                  <input
                    type="text"
                    v-model="object.phone"
                    class="form-control"
                    maxlength="50"
                    required
                  />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Close
              </button>
              <button
                type="submit"
                :disabled="disableSave"
                class="btn btn-primary"
              >
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="input-group mt-3">
      <input
        type="text"
        class="form-control"
        v-model="filter"
        placeholder="Type something to filter"
      />
    </div>

    <div class="table-responsive mt-3">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th class="text-right">
              <div
                v-show="loading"
                class="spinner-border spinner-border-sm"
                role="status"
              >
                <span class="sr-only">loading...</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredList">
            <td>{{ item.name }}</td>
            <td>{{ item.email }}</td>
            <td>{{ item.phone }}</td>
            <td class="text-right">
              <a
                title="Update"
                @click.prevent="updateContact(item)"
                data-toggle="modal"
                data-target="#modal"
                href="#"
                ><i class="fas fa-edit"></i
              ></a>
              <a
                title="Delete"
                class="ml-2"
                @click.prevent="deleteContact(item)"
                href="#"
                ><i class="fas fa-trash"></i
              ></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import util from "./script.js";
export default {
  name: "Contacts",
  data() {
    return {
      object: {},
      list: [],
      disableSave: false,
      loading: false,
      filter: "",
    };
  },
  computed: {
    filteredList() {
      var self = this;
      var result = [];
      for (var i in self.list) {
        var item = self.list[i];
        if (
          self.filter == "" ||
          item.name.toUpperCase().indexOf(self.filter.toUpperCase()) != -1 ||
          item.email.toUpperCase().indexOf(self.filter.toUpperCase()) != -1 ||
          item.phone.toUpperCase().indexOf(self.filter.toUpperCase()) != -1
        ) {
          result.push(item);
        }
      }
      return result;
    },
  },
  methods: {
    saveContact() {
      var self = this;
      self.disableSave = true;
      util.call(
        "contact/save",
        self.object,
        self,
        function (response) {
          $("#modal").modal("hide");
          self.listContacts();
        },
        function () {
          self.disableSave = false;
        }
      );
    },
    listContacts() {
      var self = this;
      self.loading = true;
      console.log(util);
      util.call("contact/list", {}, self, function (list) {
        console.log(list);
        self.list = list.data;
        self.loading = false;
      });
    },
    updateContact(item) {
      var self = this;
      self.object = JSON.parse(JSON.stringify(item));
    },
    newContact() {
      var self = this;
      self.object = {};
    },
    deleteContact(item) {
      var self = this;
      if (confirm("Are you sure you want to delete this contact?")) {
        util.call("contact/delete", item, self, function (response) {
          self.listContacts();
        });
      }
    },
  },
  mounted() {
    var self = this;
    self.listContacts();
  },
};
</script>
