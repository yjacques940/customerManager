using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class Question : BaseModel
    {
        public int? IdParent { get; set; }
        public string QuestionFr { get; set; }
        public string QuestionEn { get; set; }
        public string AnswerType { get; set; }
        public int DisplayOrder { get; set; }
    }
}
