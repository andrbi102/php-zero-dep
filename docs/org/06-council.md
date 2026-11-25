# شورا (۶ ثابت + ۴ متغیر)

> نسخه مهارت: **0.0.1** | جلسات: council-log.md (ریشه) + council/sessions/

**اعضای ثابت:** Council Architect، Council Security، Council Quality، Council Performance، Council Compliance، Release Manager
**کرسی‌های متغیر (بر اساس موضوع):** Database Lead، Crypto Specialist، Frontend Lead، Integration Lead
کرسی متغیر توسط سرپرست حوزه (سطح ۱) پر می‌شود.

## مکانیزم تصمیم‌گیری (۷ حالت)

| نوع | مکانیزم | حد نصاب | وتو |
|-----|---------|---------|------|
| معماری | اکثریت ۴ از ۶ | ۵ عضو | Council Architect |
| امنیت | اجماع کامل | ۶ عضو | مطلق — هیچ‌کس نمی‌تواند نقض کند |
| کیفیت | اکثریت ۴ از ۶ | ۵ عضو | Council Quality |
| عملکرد | اکثریت ۴ از ۶ | ۵ عضو | Council Performance |
| انطباق | اجماع ۵ از ۶ | ۶ عضو | Council Compliance |
| انتشار | اکثریت ۴ از ۶ عضو ثابت | ۵ عضو | هر عضو ثابت |
| اضطرار | Chief Architect + Security Engineer | ۲ نفر | تأیید بعدی شورا در ۲۴ ساعت |

**قاعده واحد امضا:** `approve-release` = ۲ امضا
(Release Manager + ۱ عضو شورا — SoD-2)؛ `exec-release` = ۵ امضا
(۴ عضو ثابت شورا + Release Manager — قاعده ۱۸ پرامپت اصلی و فاز F10).

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
