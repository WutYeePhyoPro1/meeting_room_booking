မင်္ဂလာပါ။။

    သုံးရတဲ့ ရည်ရွယ်ချက်
 ၁ ။ ရုံးတွင်း meeting room နှင့်ပတ်သတ်ပြီး အငြင်းပွာမှု မရှီစေရန်



    feature

user side
၁ ။ meeting room booking ခြင်း
၂ ။ တခြား dpt ရဲ့ booking တွေကို လှမ်းတောင်းခြင်း
၃ ။ booking စတင်ခြင်း

admin side
၁ ။ user create
၂ ။ booking management

For Both side
၁ ။ Dashboard view

Special Acc
၁ ။ Reception Account

    Used Libraries = [
        'fullcalendar','moment.js','sweet alert','tailwind','chartjs','jetstream','maatwebsite'

    ]

                                                                feature

    User side



    1 . meeting room booking တင်ခြင်း

        main library => fullcalendar;
        sec library  => momentjs;

        fullcalendar library (avg line:120)

            events(array)           => add data for calendar

            eventConstraint(object) => user booking လုပ်နိုင်တဲ့ အချိန် အတိုင်းအတာ
                                (eg.start =today and end = today+1month mean ဒီနေ့ကနေ တလအတွင်း booking တင်)

            eventDataTransform(callback function)
                calendar ပေါ်ကနေ တိုက်ရိုက် edit လုပ်ခွင့်ပေးလား မပေးလား(eg: event.editable)
                have to return the event(eg: return event)

            eventClick(callback function)
                calendar ပေါ်က event(booking) တွေကို click event ကို ဖမ်းနိုင်

            eventResize(callback function)
                calendar ပေါ်က event(booking) တွေကို ဖိဆွဲပြီးတိုက်ရိုက် edit ဖမ်းနိုင်

            eventDrop(callback function)
                calendar ပေါ်က event(booking) တွေကို Drag & Drop event ကို ဖမ်းနိုင်

    main necessory
        if u finish using fullcalendar call calendar.rendar(); if u give vairable as calendar


ကျန်သေးတာ backend ကနေ validation စစ်ဖို့(undone)


    2 ။ တခြား dpt ရဲ့ booking တွေကို လှမ်းတောင်းခြင်း

        file location : public->resource->view->user->today_booking

        main library => momentjs

        အခု (၁၂.၀၃.၂၀၂၄) အထိတော့ သုံးတာမရှိသေးပါဘူး

        အဓိက သုံးထားတာကတော့ js နဲ့ remaining time count လုပ်တာပါပဲ
        request လှမ်းလုပ်တာကိုတော့ laravel ရဲ့  notification ကိုသုံးထားပါတယ်
        time range ယူတာကိုတော့ css သုံးထားပါတယ်

    3 ။ booking စတင်ခြင်း(အချိန်စမှတ်ခြင်း)

            route : view->user->my_booking

        အချိန်တွေကိုတော့ user က start buttom ကို my upcoming booking page ကနေနှိပ်ရင်တော့ စတင်တယ်လို သတ်မှတ်မှာ
        ဖြစ်ပါတယ်။ start မနှိပ်လဲ အချိန် က စတင်မှာဖြစ်ပါတယ်။



                                    Admin Side


    admin side ကတော့ ထွေထွေထူးထူး ပြောစရာမရှိပါဘူး user CRUD , Manage Booking


Special Acc
    Reception Acc

    reception acc ကို user အခြား user တွေအတွက်ပါ booking တင်နိုင်မှာဖြစ်ပါတယ်


    user management နဲ့ booking management တော့လုပ်နိုင်မှာမဟုတ်ပါဘူး
    
