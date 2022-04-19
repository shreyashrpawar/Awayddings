<template>


    <div class="content-wrapper">
        <BlockUI :message="msg" :html="html" v-if="loading"></BlockUI>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Property Registration </h4>
                        <form id="propertyRegistrationFrom" method="POST" enctype="multipart/form-data"
                              @submit.prevent="formSubmit">

                            <div>

                                <section>
                                    <h3>Basic Details</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name <span style="color:red">*</span> </label>
                                                <input type="text" class="form-control"
                                                       placeholder="Enter the property name" v-model="form.name"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description <span style="color:red">*</span> </label>
                                                <textarea class="form-control" cols="30" rows="5"
                                                          v-model="form.description"
                                                          placeholder="Enter the Property Description"
                                                          required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Address <span style="color:red">*</span> </label>
                                                <input type="text" class="form-control" placeholder="Enter the Address"
                                                       v-model="form.address" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Google Embed URL <span style="color:red">*</span> </label>
                                                <textarea class="form-control" id="description" cols="30" rows="5"
                                                          name="property_gmap_embedded_code"
                                                          placeholder="Enter the Gmap Embedded URL" required
                                                          v-model="form.google_embedded_url"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Location <span style="color:red">*</span> </label>
                                                <select class="form-control" name="property_location_id"
                                                        v-model="form.location_id" required>
                                                    <option value="">Select Location</option>
                                                    <option :value="location.id" v-for="(location,i) in locations"
                                                            :key="i"> {{ location.name }}
                                                    </option>

                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Cover Image <span style="color:red">*</span> </label>
                                                <input type="file" class="form-control"
                                                       @change="handleFileUpload( $event,'cover_image','cover_image' )"
                                                       accept=".png,.jpg,.jpeg" required>
                                                <img :src="form.cover_image" class="img img-fluid">
                                            </div>


                                        </div>
                                    </div>

                                </section>

                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <h3>Images <small style="color:red;font-size: 15px">* size of image(s) should be maximum 5 MB</small></h3>
                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                    <div id="imageTypeBody">
                                        <div class="form-group">
                                            <div class="row mb-3" v-for="(category,i) in image_categories " :key="i">

                                                <div class="col-md-5 font-weight-bold">
                                                    {{ category.name }}
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="file" class="form-control" accept=".png,.jpg,.jpeg"
                                                           @change="handleFileUpload( $event,category, category.name.replace(/\s+/g, '-').toLowerCase()  )"
                                                           multiple>

                                                    <table class=" table table-sm">
                                                        <tbody>

                                                        <template v-for="(image,i) in form.images">
                                                            <tr v-if="image.category ===  category.name.replace(/\s+/g, '-').toLowerCase()">


                                                                <th><img :src="image.url"
                                                                         class="img img-fluid img-sm">


                                                                </th>
                                                                <td width="5%">
                                                                    <button class="btn  btn-sm btn-danger"
                                                                            type="button"
                                                                            @click="form.images.splice(i,1)">
                                                                        <i class="mdi mdi-trash-can "></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>


                                            <h3>Video</h3>

                                            <div class="row mb-3" v-for="(video,i) in form.videos " :key="i">

                                                <div class="col-md-5 font-weight-bold">
                                                    {{ video.name }}
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text"
                                                           class="form-control"
                                                           placeholder="https://www.youtube.com/embed/{youtube video id}"
                                                           v-model="video.url"
                                                    >
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </section>

                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <h3>Menu <small style="color:red;font-size: 15px">* size of pdf(s) should be maximum 5 MB</small> </h3>
                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                    <div id="MenuTypeBody">
                                        <div class="form-group">

                                            <div class="row mb-3" v-for="(menu,i)   in menu_categories" :key="i">

                                                <div class="col-md-5 font-weight-bold">
                                                    {{ menu.name }}
                                                </div>
                                                <div class="col-md-6">

                                                    <input type="file" class="form-control" accept=".pdf"
                                                           @change="handleFileUpload( $event,menu, menu.name.replace(/\s+/g, '-').toLowerCase()  )"
                                                           multiple>
                                                    <template v-for="(image,i) in form.images">
                                                        <tr v-if="image.category ===  menu.name.replace(/\s+/g, '-').toLowerCase()">

                                                            <th>

                                                                <a :href="image.url" target="_blank">Download</a>
                                                            </th>
                                                            <td width="5%">
                                                                <button class="btn  btn-sm btn-danger"
                                                                        type="button"
                                                                        @click="form.images.splice(i,1)">
                                                                    <i class="mdi mdi-trash-can "></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <h3> Property Rooms </h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Total No. Of Rooms</label>
                                                <input type="number" class="form-control" name=""
                                                       v-model="form.total_rooms">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Max No. Of Triple Occupancy Rooms</label>
                                                <input type="number" class="form-control" name=""
                                                       v-model="form.triple_occupancy_rooms">
                                            </div>
                                        </div>

                                    </div>
                                    <h3> Property Charges</h3>

                                    <div class="row misc" v-for="(mis,i) in form.property_charges" :key="i">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ mis.name }} Rates</label>
                                                <input type="number" class="form-control"
                                                       v-model="mis.price"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4" v-if="mis.category_id != 1  && mis.category_id != 2 ">
                                            <div class="form-group">
                                                <label>Charge applicable when room less than</label>
                                                <input type="number" class="form-control"
                                                       v-model="mis.occupancy_threshold"
                                                >
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Hotel Facilities</label>

                                                <div class="form-check" v-for="(amenity,i) in amenities">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input"
                                                               v-model="form.amenities" :value="amenity.id">
                                                        {{ amenity.name }}
                                                        <i class="input-helper"></i></label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Room Inclusion</label>

                                                <div class="form-check" v-for="(room_inclusion,i) in room_inclusions">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input"
                                                               v-model="form.room_inclusions"
                                                               :value="room_inclusion.id">
                                                        {{ room_inclusion.name }}
                                                        <i class="input-helper"></i>
                                                    </label>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="wedding_planning_decoration_budget">Wedding Planning & Decoration budget</label>
                                                <input type="number" class="form-control" id="wedding_planning_decoration_budget" v-model="form.wedding_planning_decoration_budget" required>
                                            </div>
                                        </div>

                                    </div>
                                </section>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "PropertyCreate",
    data() {
        return {
            form: {
                name: '',
                description: '',
                address: '',
                location_id: '',
                cover_image: '',
                google_embedded_url: '',
                images: [],
                videos: [],
                menus: [],
                total_rooms: 0,
                property_charges: [],
                triple_occupancy_rooms: 0,
                triple_occupancy_rate: 0,
                double_occupancy_rate: 0,
                amenities: [],
                room_inclusions: [],
                wedding_planning_decoration_budget:''

            },
            msg: 'Loading ... ',
            html: '<i class="mdi mdi-settings menu-icon mdi-48px"></i>',
            loading: true,
            locations: [],
            amenities: [],
            room_inclusions: [],
            image_categories: [],
            video_categories: [],
            menu_categories: [],
            property_chargable_categories: []
        }
    },
    mounted() {
        this.getLocations();
        this.getHotelFacilitiesAndRoomInclusions();
        this.getImageCategories();
        this.getMenuCategories();
        this.getVideoCategories();
        this.getPropertyChargableCategories();
    },
    methods: {
        getPropertyChargableCategories() {
            axios.get('/api/property-chargable-category')
                .then(resp => {
                    console.log(resp.data.data);
                    this.property_chargable_categories = resp.data.data;
                    this.property_chargable_categories.forEach(pcc => {
                        this.form.property_charges.push({
                            'category_id': pcc.id,
                            'price': 0,
                            'name': pcc.name,
                            'occupancy_threshold': 0
                        })
                    })
                })
        },
        getMenuCategories() {
            axios.get('/api/menu-category')
                .then(resp => {
                    console.log(resp.data.data);
                    this.menu_categories = resp.data.data;
                })
        },
        getVideoCategories() {
            axios.get('/api/video-category')
                .then(resp => {
                    console.log(resp.data.data);
                    this.video_categories = resp.data.data;
                    this.video_categories.forEach( video => {
                        this.form.videos.push({
                            name: video.name,
                            category_id: video.id,
                            url: ''
                        });
                    })

                })
        },
        getImageCategories() {
            axios.get('/api/image-category')
                .then(resp => {
                    console.log(resp.data.data);
                    this.image_categories = resp.data.data;
                })
        },
        getHotelFacilitiesAndRoomInclusions() {
            axios.get('/api/amenities-room-inclusion')
                .then(resp => {
                    console.log(resp.data.data);
                    this.amenities = resp.data.data.amenities;
                    this.room_inclusions = resp.data.data.room_inclusions;
                })
        },
        getLocations() {
            axios.get('/api/locations')
                .then(resp => {
                    console.log(resp);
                    this.locations = resp.data.data;
                    this.loading = false;
                })
        },

        async handleFileUpload(event, data, category) {
            this.files = event.target.files;
            this.loading = true;
            for (let i = 0; i < this.files.length; i++) {
                if(this.files[i].size < 5000000){
                    let formData = new FormData();

                    formData.append('file', this.files[i]);
                    try {
                        let resp = await axios.post('/media',
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        )
                        if (resp.data.success) {
                            if(data == 'cover_image'){
                                this.form.cover_image = resp.data.url
                            }else{
                                this.form.images.push({
                                    category: category,
                                    category_id: data.id,
                                    url: resp.data.url
                                })
                            }

                        }
                    } catch (err) {
                        if (err.response) {
                            this.loading = false;
                            this.$swal.fire({
                                text: err.response.data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }

                    }
                }else{
                    this.$swal.fire({
                        text: "File size should be less than 5MB",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }



            }
            this.loading = false;
        },
        formSubmit() {
            let formData = this.form;
            axios.post('/property', formData)
                .then(resp => {
                    console.log(resp);
                    if (resp.data.success) {
                        if (resp.data.success) {
                            this.$swal.fire({
                                text: resp.data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/property'
                                }
                            })

                        }else{
                            this.$swal.fire({
                                text: resp.data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/property'
                                }
                            })

                        }
                    }
                })
                .catch((err) => {
                    this.$swal({
                        text: 'Oops something went wrong !',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            window.location.href= '/property';
                        } else {
                            //if no clicked => do something else
                        }
                    });
                })
        }
    }
}
</script>

<style scoped>

</style>
