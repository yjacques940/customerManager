using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class AboutText : BaseModel
    {
        public string TitleFr { get; set; }
        public string TitleEn { get; set; }
        public string DescrFr { get; set; }
        public string DescrEn { get; set; }
    }
}
