using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using NodaTime;

namespace WebApi.Validators
{
    public static class DateConverter
    {
        public static DateTime CurrentEasternDateTime()
        {
            var easternTimeZone = DateTimeZoneProviders.Tzdb["America/New_York"];
            return Instant.FromDateTimeUtc(DateTime.UtcNow)
                      .InZone(easternTimeZone)
                      .ToDateTimeUnspecified();
        }
    }
}
